<?php 
session_start();
require_once 'conexao.php';


// VERIFICA SE O USUARIO TEM PERMISSAO
if (!isset($_SESSION['cargo']) || ($_SESSION['cargo'] != "Gerente")) {
    echo "Acesso Negado!";
    header("Location: dashboard.php");
    exit();
}
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
    ],
    'Atendente' => [
        ['href' => 'dashboard.php', 'icon' => '👤', 'text' => 'Perfil'],
        ['href' => 'cadastro-cliente.php', 'icon' => '📋', 'text' => 'Cadastro Cliente'],
        ['href' => 'cadastro-ordem_serv.php', 'icon' => '🛠️', 'text' => 'Cadastro de<br>Ordem de Serviço'],
        ['href' => 'ordem_serv.php', 'icon' => '💼', 'text' => 'Ordem de serviço'],
        ['href' => 'estoque.php', 'icon' => '📦', 'text' => 'Estoque'],
        ['href' => 'fornecedor.php', 'icon' => '🔗', 'text' => 'Fornecedores'],
        ['href' => 'suporte.php', 'icon' => '🆘', 'text' => 'Suporte'],
        ['href' => 'logout.php', 'icon' => '🚪', 'text' => 'Sair']
    ],
    'Tecnico' => [    
        ['href' => 'dashboard.php', 'icon' => '👤', 'text' => 'Perfil'],
        ['href' => 'ordem_serv.php', 'icon' => '💼', 'text' => 'Ordem de serviço'],
        ['href' => 'suporte.php', 'icon' => '🆘', 'text' => 'Suporte'],
        ['href' => 'logout.php', 'icon' => '🚪', 'text' => 'Sair']
    ],
  ];
// Obter o menu correspondente ao cargo do usuário
$menuItems = isset($_SESSION['cargo']) && isset($menus[$_SESSION['cargo']]) ? $menus[$_SESSION['cargo']] : [];

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
</head>
<body>
  <!-- Sidebar fixa -->
<nav class="sidebar">
    <div class="logo">
      <img src="img/logo.png" alt="Logo do sistema">
    </div>
    <ul class="menu">
      <?php foreach ($menuItems as $item): ?>
        <li><a href="<?php echo $item['href']; ?>"><?php echo $item['icon']; ?> <span><?php echo $item['text']; ?></span></a></li>
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
        <option value="1">Janeiro</option>
        <option value="2">Fevereiro</option>
        <option value="3">Março</option>
        <option value="4">Abril</option>
        <option value="5">Maio</option>
        <option value="6">Junho</option>
        <option value="7">Julho</option>
        <option value="8">Agosto</option>
        <option value="9">Setembro</option>
        <option value="10">Outubro</option>
        <option value="11">Novembro</option>
        <option value="12">Dezembro</option>
      </select>

      <label for="ano">Ano:</label>
      <input type="number" id="ano" min="2000" max="2100" value="2025">

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
        <div class="filtro-pecas">
          <h2>Saída de Peças</h2>
          <div class="busca-peca-topo">
            <input list="listaPecas" id="pecaSelecionada" oninput="filtrarPorPeca()" placeholder="🔍 Buscar peça para saída">
            <button onclick="confirmarBuscaPeca()">Confirmar</button>
          </div>
          <datalist id="listaPecas">
            <option value="todas">Todas as Peças</option>
            <option value="processador">Processador</option>
            <option value="memoria">Memória RAM</option>
            <option value="placa_mae">Placa Mãe</option>
            <option value="hd">HD/SSD</option>
            <option value="fonte">Fonte</option>
            <option value="gabinete">Gabinete</option>
          </datalist>
        </div>
        <canvas id="graficoSaidaPecas"></canvas>
      </div>
    </div>
  </div>

  <script>
    // Dados fictícios para as peças
    const dadosPecas = {
      todas: [30, 45, 22, 38],
      processador: [5, 8, 3, 7],
      memoria: [8, 12, 5, 10],
      placa_mae: [4, 6, 3, 5],
      hd: [7, 10, 6, 8],
      fonte: [3, 5, 2, 4],
      gabinete: [3, 4, 3, 4]
    };

    const chartPedidos = new Chart(document.getElementById('graficoPedidos'), {
      type: 'bar',
      data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr'],
        datasets: [{
          label: 'Pedidos',
          data: [15, 20, 10, 25],
          backgroundColor: '#03dac6',
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#fff' } } },
        scales: {
          x: { ticks: { color: '#fff' } },
          y: { ticks: { color: '#fff' } }
        }
      }
    });

    const chartFinancas = new Chart(document.getElementById('graficoFinancas'), {
      type: 'line',
      data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr'],
        datasets: [{
          label: 'R$ Faturado',
          data: [1500, 2300, 1100, 2800],
          backgroundColor: '#bb86fc',
          borderColor: '#854ec2',
          borderWidth: 3,
          tension: 0.4,
          fill: false
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#fff' } } },
        scales: {
          x: { ticks: { color: '#fff' } },
          y: {
            ticks: {
              color: '#fff',
              callback: function(value) {
                return 'R$ ' + value.toLocaleString('pt-BR');
              }
            }
          }
        }
      }
    });

    const chartSaidaPecas = new Chart(document.getElementById('graficoSaidaPecas'), {
      type: 'bar',
      data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr'],
        datasets: [{
          label: 'Peças Saídas',
          data: dadosPecas.todas,
          backgroundColor: '#ff9800',
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#fff' } } },
        scales: {
          x: { ticks: { color: '#fff' } },
          y: { ticks: { color: '#fff' } }
        }
      }
    });

    function filtrarPorPeca() {
      const valorDigitado = document.getElementById('pecaSelecionada').value.toLowerCase().replace(/\s/g, '_');
      const nomeExibido = document.getElementById('pecaSelecionada').value;

      const chavePeca = Object.keys(dadosPecas).includes(valorDigitado) ? valorDigitado : 'todas';

      // Atualiza os dados do gráfico de saída de peças com base na peça selecionada
      const dias = chartSaidaPecas.data.labels; // Obtém os dias filtrados
      const saidaPecas = dias.map(() => Math.floor(Math.random() * 15 + 5)); // Gera novos dados para cada dia
      dadosPecas.todas = saidaPecas; // Atualiza os dados de saída de peças

      chartSaidaPecas.data.datasets[0].data = dadosPecas[chavePeca]; // Atualiza os dados do gráfico
      chartSaidaPecas.data.datasets[0].label = chavePeca === 'todas' ? 'Peças Saídas' : `Saída de ${nomeExibido}`;
      chartSaidaPecas.update(); // Atualiza o gráfico
    }

    function confirmarBuscaPeca() {
      const valorDigitado = document.getElementById('pecaSelecionada').value.toLowerCase().replace(/\s/g, '_');
      const datalist = Array.from(document.querySelectorAll('#listaPecas option')).map(opt => opt.value.toLowerCase().replace(/\s/g, '_'));

      if (!datalist.includes(valorDigitado)) {
        alert('⚠️ Peça inválida! Por favor selecione uma peça da lista.');
        return;
      }

      document.getElementById('pecaSelecionada').value = document.querySelector(`#listaPecas option[value="${valorDigitado.replace(/_/g, ' ')}"]`)?.value || 'todas';
      
      filtrarPorPeca();
    }

    function filtrarRelatorio() {
      const dataInicio = document.getElementById('dataInicio').value;
      const dataFim = document.getElementById('dataFim').value;
      const mes = document.getElementById('mes').value;
      const ano = document.getElementById('ano').value;

      let dias = [];
      let pedidos = [];
      let faturamento = [];
      let saidaPecas = [];

      if (dataInicio && dataFim) {
        if (dataInicio > dataFim) {
          alert('⚠️ A data de início não pode ser maior que a data de fim.');
          return;
        }

        dias = gerarDiasEntreDatas(dataInicio, dataFim);
        pedidos = dias.map(() => Math.floor(Math.random() * 10 + 1));
        faturamento = dias.map(() => Math.floor(Math.random() * 1500 + 500));
        saidaPecas = dias.map(() => Math.floor(Math.random() * 15 + 5));
        dadosPecas.todas = saidaPecas;

        alert(`📌 Filtro aplicado de ${dataInicio} até ${dataFim}`);
      } else if (mes && ano) {
        const mesInt = parseInt(mes);
        const diasNoMes = new Date(ano, mesInt, 0).getDate();
        const nomeMes = document.querySelector(`#mes option[value="${mes}"]`).textContent;

        dias = Array.from({ length: diasNoMes }, (_, i) => {
          const dia = (i + 1).toString().padStart(2, '0');
          return `${dia}/${mes.padStart(2, '0')}`;
        });

        pedidos = dias.map(() => Math.floor(Math.random() * 10 + 1));
        faturamento = dias.map(() => Math.floor(Math.random() * 1500 + 500));
        saidaPecas = dias.map(() => Math.floor(Math.random() * 15 + 5));
        dadosPecas.todas = saidaPecas;

        alert(`📌 Filtro aplicado para ${nomeMes}/${ano}`);
      } else if (!mes && ano) {
        const nomesMeses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

        pedidos = Array.from({ length: 12 }, () => Math.floor(Math.random() * 100 + 50));
        faturamento = Array.from({ length: 12 }, () => Math.floor(Math.random() * 15000 + 5000));
        saidaPecas = Array.from({ length: 12 }, () => Math.floor(Math.random() * 150 + 30));
        dadosPecas.todas = saidaPecas;

        alert(`📌 Filtro aplicado para o ano de ${ano}`);
        dias = nomesMeses;
      } else {
        alert('⚠️ Preencha intervalo de datas ou selecione mês e/ou ano.');
        return;
      }

      atualizarGraficos(dias, pedidos, faturamento);
      filtrarPorPeca();
    }

    function limparFiltros() {
      document.getElementById('dataInicio').value = '';
      document.getElementById('dataFim').value = '';
      document.getElementById('mes').value = '';
      document.getElementById('ano').value = '2025';
      document.getElementById('pecaSelecionada').value = 'todas';

      dadosPecas.todas = [30, 45, 22, 38];
      
      atualizarGraficos(
        ['Jan', 'Fev', 'Mar', 'Abr'],
        [15, 20, 10, 25],
        [1500, 2300, 1100, 2800]
      );
      
      filtrarPorPeca();
    }

    function atualizarGraficos(labels, dadosPedidos, dadosFinancas) {
      chartPedidos.data.labels = labels;
      chartPedidos.data.datasets[0].data = dadosPedidos;
      chartPedidos.update();

      chartFinancas.data.labels = labels;
      chartFinancas.data.datasets[0].data = dadosFinancas;
      chartFinancas.update();

      // Atualiza o gráfico de saída de peças com base nos dados filtrados
      chartSaidaPecas.data.labels = labels;
      chartSaidaPecas.data.datasets[0].data = dadosPecas.todas;
      chartSaidaPecas.update();
    }

    function gerarDiasEntreDatas(inicio, fim) {
      const dias = [];
      const dataAtual = new Date(inicio);
      const dataLimite = new Date(fim);

      while (dataAtual <= dataLimite) {
        const dia = dataAtual.getDate().toString().padStart(2, '0');
        const mes = (dataAtual.getMonth() + 1).toString().padStart(2, '0');
        dias.push(`${dia}/${mes}`);
        dataAtual.setDate(dataAtual.getDate() + 1);
      }

      return dias;
    }
  </script>

  <script>
    const links = document.querySelectorAll('.sidebar .menu li a');
    const currentPage = window.location.pathname.split('/').pop();

    links.forEach(link => {
      if (link.getAttribute('href') === currentPage) {
        link.classList.add('active');
      }
    });
  </script>
</body>
</html>
