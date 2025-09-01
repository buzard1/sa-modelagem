<?php 
session_start();
require_once 'conexao.php';

// VERIFICA SE O USUARIO TEM PERMISSAO
if (!isset($_SESSION['cargo']) || ($_SESSION['cargo'] != "Gerente")) {
    header("Location: dashboard.php");
    exit();
}

// ----------------- API INTERNA PARA O JS (retorna JSON) -----------------
if (isset($_GET['acao']) && $_GET['acao'] === 'buscar') {
    header('Content-Type: application/json');

    $dataInicio = $_GET['dataInicio'] ?? null;
    $dataFim    = $_GET['dataFim'] ?? null;
    $mes        = $_GET['mes'] ?? null;
    $ano        = $_GET['ano'] ?? date("Y");

    $response = [
        "labels" => [],
        "pedidos" => [],
        "financas" => [],
        "pecas" => []
    ];

    // -------------------- PEDIDOS --------------------
    if ($dataInicio && $dataFim) {
        $sql = "SELECT DATE(data_entrada) as dia, COUNT(*) as total 
                FROM ordem_serv 
                WHERE data_entrada BETWEEN :ini AND :fim
                GROUP BY DATE(data_entrada)
                ORDER BY dia";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':ini' => $dataInicio, ':fim' => $dataFim]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $response["labels"][] = date("d/m", strtotime($row['dia']));
            $response["pedidos"][] = (int)$row['total'];
        }
    } elseif ($mes && $ano) {
        $sql = "SELECT DAY(data_entrada) as dia, COUNT(*) as total
                FROM ordem_serv
                WHERE MONTH(data_entrada) = :mes AND YEAR(data_entrada) = :ano
                GROUP BY DAY(data_entrada)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':mes' => $mes, ':ano' => $ano]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $response["labels"][] = str_pad($row['dia'], 2, "0", STR_PAD_LEFT)."/$mes";
            $response["pedidos"][] = (int)$row['total'];
        }
    } else {
        $sql = "SELECT MONTH(data_entrada) as mes, COUNT(*) as total
                FROM ordem_serv
                WHERE YEAR(data_entrada) = :ano
                GROUP BY MONTH(data_entrada)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':ano' => $ano]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $nomesMeses = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
        foreach ($result as $row) {
            $response["labels"][] = $nomesMeses[$row['mes']-1];
            $response["pedidos"][] = (int)$row['total'];
        }
    }

    // -------------------- FINANÇAS --------------------
    $sql = "SELECT SUM(valor) as faturado 
            FROM ordem_serv 
            WHERE YEAR(data_entrada) = :ano AND status = 'Concluído'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ano' => $ano]);
    $response["financas"][] = (int)$stmt->fetchColumn();

    // -------------------- PEÇAS --------------------
    // Usando a tabela servico_produto para obter as saídas de peças
    $sql = "SELECT p.nome_produto, SUM(sp.quantidade) as total
            FROM servico_produto sp
            JOIN produto p ON p.id_produto = sp.id_produto
            JOIN ordem_serv os ON os.id_ordem_serv = sp.id_ordem_serv
            WHERE YEAR(os.data_entrada) = :ano
            GROUP BY p.nome_produto
            ORDER BY total DESC
            LIMIT 10"; // Limitando a 10 peças mais vendidas
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ano' => $ano]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $row) {
        $response["pecas"][$row['nome_produto']] = (int)$row['total'];
    }

    echo json_encode($response);
    exit();
}

// ----------------- MENU LATERAL -----------------
$menus = [
    'Gerente' => [
        ['href' => 'dashboard.php', 'icon' => '👤', 'text' => 'Perfil'],
        ['href' => 'cadastro-cliente.php', 'icon' => '📋', 'text' => 'Cadastro Cliente'],
        ['href' => 'cadastro-ordem_serv.php', 'icon' => '🛠️', 'text' => 'Cadastro de<br>Ordem de Serviço'],
        ['href' => 'ordem_serv.php', 'icon' => '💼', 'text' => 'Ordem de serviço'],
        ['href' => 'relatorio.php', 'icon' => '📊', 'text' => 'Relatórios'],
        ['href' => 'estoque.php', 'icon' => '📦', 'text' => 'Estoque'],
        ['href' => 'usuarios.php', 'icon' => '👥', 'text' => 'Usuários'],
        ['href' => 'fornecedor.php', 'icon' => '🔗', 'text' => 'Fornecedores'],
        ['href' => 'suporte.php', 'icon' => '🆘', 'text' => 'Suporte'],
        ['href' => 'logout.php', 'icon' => '🚪', 'text' => 'Sair']
    ]
];
$menuItems = $menus[$_SESSION['cargo']] ?? [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Relatórios</title>
  <link rel="stylesheet" href="css/sidebar.css" />
  <link rel="stylesheet" href="css/relatorio.css" />
  <link rel="icon" href="img/logo.png" type="image/png">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    /* Estilos básicos para a página */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    body {
      background-color: #1e1e2e;
      color: #fff;
      display: flex;
      min-height: 100vh;
    }
    
    /* Sidebar */
    .sidebar {
      width: 250px;
      background-color: #2c2c3f;
      padding: 20px;
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
    }
    
    .logo {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .logo img {
      max-width: 100px;
    }
    
    .menu {
      list-style: none;
    }
    
    .menu li {
      margin-bottom: 10px;
    }
    
    .menu a {
      color: #ddd;
      text-decoration: none;
      display: flex;
      align-items: center;
      padding: 12px;
      border-radius: 8px;
      transition: background-color 0.3s;
    }
    
    .menu a:hover {
      background-color: #3a3a52;
    }
    
    .menu a.active {
      background-color: #bb86fc;
      color: #000;
    }
    
    .menu .icon {
      margin-right: 10px;
      font-size: 20px;
    }
    
    /* Container principal */
    .container {
      margin-left: 250px;
      padding: 30px;
      width: calc(100% - 250px);
    }
    
    h1 {
      margin-bottom: 30px;
      font-size: 28px;
      color: #bb86fc;
    }
    
    /* Filtros */
    .filtros {
      background-color: #2c2c3f;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 30px;
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      align-items: center;
    }
    
    .filtros label {
      font-weight: bold;
    }
    
    .filtros input, .filtros select {
      padding: 8px 12px;
      border-radius: 5px;
      border: 1px solid #444;
      background-color: #1e1e2e;
      color: #fff;
    }
    
    .filtros button {
      padding: 8px 16px;
      border: none;
      border-radius: 5px;
      background-color: #03dac6;
      color: #000;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s;
    }
    
    .filtros button:hover {
      background-color: #00c9b3;
    }
    
    .filtros button:last-child {
      background-color: #ff9800;
    }
    
    .filtros button:last-child:hover {
      background-color: #e68900;
    }
    
    /* Gráficos */
    .graficos {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
      gap: 20px;
    }
    
    .grafico-box {
      background-color: #2c2c3f;
      padding: 20px;
      border-radius: 10px;
    }
    
    .grafico-box h2 {
      margin-bottom: 15px;
      font-size: 20px;
      color: #bb86fc;
    }
    
    .grafico-box canvas {
      width: 100% !important;
      height: 250px !important;
    }
    
    @media (max-width: 1024px) {
      .sidebar {
        width: 200px;
      }
      
      .container {
        margin-left: 200px;
        width: calc(100% - 200px);
      }
      
      .graficos {
        grid-template-columns: 1fr;
      }
    }
    
    @media (max-width: 768px) {
      .sidebar {
        width: 70px;
        padding: 10px;
      }
      
      .sidebar span {
        display: none;
      }
      
      .container {
        margin-left: 70px;
        width: calc(100% - 70px);
        padding: 15px;
      }
      
      .filtros {
        flex-direction: column;
        align-items: stretch;
      }
    }
  </style>
</head>
<body>
  <!-- Sidebar fixa -->
  <nav class="sidebar">
    <div class="logo">
      <img src="img/logo.png" alt="Logo do sistema">
    </div>
    <ul class="menu">
      <?php foreach ($menuItems as $item): ?>
        <li><a href="<?php echo $item['href']; ?>"><span class="icon"><?php echo $item['icon']; ?></span> <span><?php echo $item['text']; ?></span></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <div class="container">
    <h1>📊 Relatórios</h1>

    <div class="filtros">
      <label for="dataInicio">Data Início:</label>
      <input type="date" id="dataInicio" name="dataInicio">

      <label for="dataFim">Data Fim:</label>
      <input type="date" id="dataFim" name="dataFim">

      <label for="mes">Mês:</label>
      <select id="mes">
        <option value="">Todos</option>
        <?php 
        $meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho',
                  'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
        foreach ($meses as $i => $nome) {
            echo "<option value='".($i+1)."'>$nome</option>";
        }
        ?>
      </select>

      <label for="ano">Ano:</label>
      <input type="number" id="ano" min="2000" max="2100" value="<?php echo date("Y"); ?>">

      <button onclick="filtrarRelatorio()">Filtrar</button>
      <button onclick="limparFiltros()">Limpar Filtros</button>
    </div>

    <div class="graficos">
      <div class="grafico-box">
        <h2>Pedidos</h2>
        <canvas id="graficoPedidos"></canvas>
      </div>

      <div class="grafico-box">
        <h2>Finanças</h2>
        <canvas id="graficoFinancas"></canvas>
      </div>

      <div class="grafico-box">
        <h2>Saída de Peças</h2>
        <canvas id="graficoSaidaPecas"></canvas>
      </div>
    </div>
  </div>

  <script>
    // Instancia os gráficos vazios
    const chartPedidos = new Chart(document.getElementById('graficoPedidos'), {
      type: 'bar',
      data: { labels: [], datasets: [{ label: 'Pedidos', data: [], backgroundColor: '#03dac6', borderRadius: 6 }] },
      options: { responsive: true, plugins: { legend: { labels: { color: '#fff' } } }, scales: { x: { ticks: { color: '#fff' } }, y: { ticks: { color: '#fff' } } } }
    });

    const chartFinancas = new Chart(document.getElementById('graficoFinancas'), {
      type: 'line',
      data: { labels: [], datasets: [{ label: 'R$ Faturado', data: [], backgroundColor: '#bb86fc', borderColor: '#854ec2', borderWidth: 3, tension: 0.4, fill: false }] },
      options: { responsive: true, plugins: { legend: { labels: { color: '#fff' } } }, scales: { x: { ticks: { color: '#fff' } }, y: { ticks: { color: '#fff', callback: v => 'R$ ' + v.toLocaleString('pt-BR') } } } }
    });

    const chartSaidaPecas = new Chart(document.getElementById('graficoSaidaPecas'), {
      type: 'bar',
      data: { labels: [], datasets: [{ label: 'Peças Saídas', data: [], backgroundColor: '#ff9800', borderRadius: 6 }] },
      options: { responsive: true, plugins: { legend: { labels: { color: '#fff' } } }, scales: { x: { ticks: { color: '#fff' } }, y: { ticks: { color: '#fff' } } } }
    });

    async function filtrarRelatorio() {
      const dataInicio = document.getElementById('dataInicio').value;
      const dataFim = document.getElementById('dataFim').value;
      const mes = document.getElementById('mes').value;
      const ano = document.getElementById('ano').value;

      const params = new URLSearchParams({ acao: 'buscar', dataInicio, dataFim, mes, ano });
      const resp = await fetch('relatorio.php?' + params.toString());
      const dados = await resp.json();

      atualizarGraficos(dados.labels, dados.pedidos, dados.financas, dados.pecas);
    }

    function atualizarGraficos(labels, pedidos, financas, pecas) {
      chartPedidos.data.labels = labels;
      chartPedidos.data.datasets[0].data = pedidos;
      chartPedidos.update();

      chartFinancas.data.labels = labels;
      chartFinancas.data.datasets[0].data = financas;
      chartFinancas.update();

      chartSaidaPecas.data.labels = Object.keys(pecas);
      chartSaidaPecas.data.datasets[0].data = Object.values(pecas);
      chartSaidaPecas.update();
    }

    function limparFiltros() {
      document.getElementById('dataInicio').value = '';
      document.getElementById('dataFim').value = '';
      document.getElementById('mes').value = '';
      document.getElementById('ano').value = new Date().getFullYear();
      filtrarRelatorio();
    }

    // Carrega dados iniciais
    filtrarRelatorio();
  </script>

  <script> 
    const links = document.querySelectorAll('.sidebar .menu li a');
    const currentPage = window.location.pathname.split('/').pop();
    links.forEach(link => { if (link.getAttribute('href') === currentPage) link.classList.add('active'); });
  </script>
</body>
</html>