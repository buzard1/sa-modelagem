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
    $pecaBusca  = $_GET['pecaBusca'] ?? null;

    $response = [
        "labels" => [],
        "pedidos" => [],
        "financas" => []
    ];

    $nomesMeses = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

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
                GROUP BY DAY(data_entrada)
                ORDER BY dia";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':mes' => $mes, ':ano' => $ano]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $diasNoMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
        $pedidosPorDia = array_fill(1, $diasNoMes, 0);
        
        foreach ($result as $row) {
            $pedidosPorDia[$row['dia']] = (int)$row['total'];
        }
        
        for ($dia = 1; $dia <= $diasNoMes; $dia++) {
            $response["labels"][] = str_pad($dia, 2, "0", STR_PAD_LEFT)."/".str_pad($mes, 2, "0", STR_PAD_LEFT);
            $response["pedidos"][] = $pedidosPorDia[$dia];
        }
    } else {
        $response["labels"] = $nomesMeses;
        
        $sql = "SELECT MONTH(data_entrada) as mes, COUNT(*) as total
                FROM ordem_serv
                WHERE YEAR(data_entrada) = :ano
                GROUP BY MONTH(data_entrada)
                ORDER BY mes";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':ano' => $ano]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pedidosPorMes = array_fill(0, 12, 0);
        foreach ($result as $row) {
            $pedidosPorMes[$row['mes']-1] = (int)$row['total'];
        }
        
        $response["pedidos"] = $pedidosPorMes;
    }

    // -------------------- FINANÇAS --------------------
    if ($dataInicio && $dataFim) {
        $sql = "SELECT COALESCE(SUM(valor), 0) as faturado 
                FROM ordem_serv 
                WHERE data_entrada BETWEEN :ini AND :fim AND status = 'Concluído'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':ini' => $dataInicio, ':fim' => $dataFim]);
        $faturado = $stmt->fetchColumn();
        
        $response["financas"][] = (float)$faturado;
        $response["labels_financas"] = ["Período Selecionado"];
        
    } elseif ($mes && $ano) {
        $sql = "SELECT DAY(data_entrada) as dia, COALESCE(SUM(valor), 0) as faturado
                FROM ordem_serv 
                WHERE MONTH(data_entrada) = :mes AND YEAR(data_entrada) = :ano AND status = 'Concluído'
                GROUP BY DAY(data_entrada)
                ORDER BY dia";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':mes' => $mes, ':ano' => $ano]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $diasNoMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
        $financasPorDia = array_fill(1, $diasNoMes, 0);
        
        foreach ($result as $row) {
            $financasPorDia[$row['dia']] = (float)$row['faturado'];
        }
        
        $response["financas"] = array_values($financasPorDia);
        $response["labels_financas"] = [];
        for ($dia = 1; $dia <= $diasNoMes; $dia++) {
            $response["labels_financas"][] = str_pad($dia, 2, "0", STR_PAD_LEFT)."/".str_pad($mes, 2, "0", STR_PAD_LEFT);
        }
        
    } else {
        $response["labels_financas"] = $nomesMeses;
        
        $sql = "SELECT MONTH(data_entrada) as mes, COALESCE(SUM(valor), 0) as faturado
                FROM ordem_serv
                WHERE YEAR(data_entrada) = :ano AND status = 'Concluído'
                GROUP BY MONTH(data_entrada)
                ORDER BY mes";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':ano' => $ano]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $financasPorMes = array_fill(0, 12, 0);
        foreach ($result as $row) {
            $financasPorMes[$row['mes']-1] = (float)$row['faturado'];
        }
        
        $response["financas"] = $financasPorMes;
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
    /* (mantive os estilos iguais, só sem necessidade de mexer em nada) */
    body { background-color: #121212; color: #fff; display: flex; min-height: 100vh; }
    .container { margin-left: 220px; padding: 25px; width: calc(100% - 220px); }
    .graficos { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; }
    .grafico-box { background-color: #1f1f1f; padding: 20px; border-radius: 10px; }
    .grafico-box h2 { margin-bottom: 15px; font-size: 20px; color: #bb86fc; }
    .grafico-box canvas { width: 100% !important; height: 250px !important; }
  </style>
</head>
<body>
  <nav class="sidebar">
    <div class="logo">
      <img src="img/logo.png" alt="Logo do sistema">
    </div>
    <ul class="menu">
      <?php foreach ($menuItems as $item): ?>
        <li><a href="<?php echo $item['href']; ?>" <?php echo $item['href'] === 'relatorio.php' ? 'class="active"' : ''; ?>><?php echo $item['icon']; ?> <span><?php echo $item['text']; ?></span></a></li>
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
    </div>
  </div>

  <script>
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

    async function filtrarRelatorio() {
      const dataInicio = document.getElementById('dataInicio').value;
      const dataFim = document.getElementById('dataFim').value;
      const mes = document.getElementById('mes').value;
      const ano = document.getElementById('ano').value;

      const params = new URLSearchParams({ acao: 'buscar', dataInicio, dataFim, mes, ano });
      
      const resp = await fetch('relatorio.php?' + params.toString());
      const dados = await resp.json();

      atualizarGraficos(dados.labels, dados.pedidos, dados.financas, dados.labels_financas);
    }

    function atualizarGraficos(labels, pedidos, financas, labels_financas) {
      chartPedidos.data.labels = labels;
      chartPedidos.data.datasets[0].data = pedidos;
      chartPedidos.update();

      chartFinancas.data.labels = labels_financas || labels;
      chartFinancas.data.datasets[0].data = financas;
      chartFinancas.update();
    }

    function limparFiltros() {
      document.getElementById('dataInicio').value = '';
      document.getElementById('dataFim').value = '';
      document.getElementById('mes').value = '';
      document.getElementById('ano').value = new Date().getFullYear();
      filtrarRelatorio();
    }

    filtrarRelatorio();
  </script>

  <script> 
    const links = document.querySelectorAll('.sidebar .menu li a');
    const currentPage = window.location.pathname.split('/').pop();
    links.forEach(link => { if (link.getAttribute('href') === currentPage) link.classList.add('active'); });
  </script>
</body>
</html>
