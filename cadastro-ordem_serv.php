<?php 
session_start();
require_once 'conexao.php';

// VERIFICA SE O USUARIO TEM PERMISSAO
if (!isset($_SESSION['cargo']) || ($_SESSION['cargo'] != "Gerente" && $_SESSION['cargo'] != "Atendente")) {
    echo "Acesso Negado!";
    header("Location: dashboard.php");
    exit();
}

// Definir os menus com base no cargo
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

// PROCESSAMENTO DO FORMULÁRIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $cpfcliente  = $_POST["cpfcliente"];
  $aparelho    = $_POST["aparelho"];
  $servico = $_POST["servico"];
  $problema    = $_POST["problema"];
  $valor       = $_POST["valor"];
  $pagamento   = $_POST["Pagamento"];
  $status      = $_POST["status"];
  $idusuario   = isset($_SESSION['idusuario']) ? $_SESSION['idusuario'] : 1; // usuário logado

  // Conexão
  $pdo = new PDO("mysql:host=localhost;dbname=sa_mobilerepair", "root", "");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Buscar ID do cliente pelo CPF
  $stmtCliente = $pdo->prepare("SELECT cpf FROM cliente WHERE cpf = :cpf");
  $stmtCliente->bindParam(':cpf', $cpfcliente);
  $stmtCliente->execute();
  $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

  if ($cliente) {
      $idcliente = $cliente['cpf'];

      // Inserir ordem de serviço
      $stmt = $pdo->prepare("
          INSERT INTO ordem_serv 
          (aparelho, servico, status, valor, tipo_pagamento, problema, cpf, idusuario, data_entrada) 
          VALUES (:aparelho, :servico, :status, :valor, :tipo_pagamento, :problema, :cpf, :idusuario, NOW())
      ");

      $stmt->bindParam(':aparelho', $aparelho);
      $stmt->bindParam(':servico', $servico);
      $stmt->bindParam(':status', $status);
      $stmt->bindParam(':valor', $valor);
      $stmt->bindParam(':tipo_pagamento', $pagamento);
      $stmt->bindParam(':problema', $problema);
      $stmt->bindParam(':cpf', $idcliente); 
      $stmt->bindParam(':idusuario', $idusuario);

      if ($stmt->execute()) {
          echo "<script>alert('✅ Ordem de serviço cadastrada com sucesso!'); window.location='ordem_serv.php';</script>";
      } else {
          echo "<script>alert('❌ Erro ao cadastrar ordem de serviço!');</script>";
      }
  } else {
      echo "<script>alert('⚠️ Cliente não encontrado para o CPF informado!');</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cadastro de Ordem de Serviço</title>
  <link rel="stylesheet" href="css/sidebar.css" />
  <link rel="stylesheet" href="css/form.css" />
  <link rel="icon" href="img/logo.png" type="image/png">
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
  
  <div class="form-container">
    <h2>🛠️ Cadastro de Ordem de serviço</h2>
    <form method="POST">
      <label for="cpfcliente">CPF do Cliente:</label>
      <input type="text" id="cpfcliente" name="cpfcliente" maxlength="14" required placeholder="000.000.000-00"/>

      <label for="aparelho">Aparelho:</label>
      <input type="text" id="aparelho" name="aparelho" required />

      <label for="problema">Problema Relatado:</label>
      <textarea id="problema" name="problema" rows="4" required></textarea>

      <label for="servico">Serviço a ser prestado:</label>
      <input type="text" id="servico" name="servico" required />
      
      <label for="valor">Valor (R$)</label>
      <input type="number" id="valor" name="valor" placeholder="Ex: 120.00" step="0.01" min="0" required />

      <label for="Pagamento">Forma de pagamento:</label>
      <select id="Pagamento" name="Pagamento">
        <option value="Pix">Pix</option>
        <option value="Dinheiro">Dinheiro</option>
        <option value="Cartão">Cartão de crédito/débito</option>
        <option value="Boleto">Boleto</option>
      </select>

      <label for="status">Status:</label>
      <select id="status" name="status">
        <option value="pendente">Pendente</option>
        <option value="em-andamento">Em andamento</option>
        <option value="concluido">Concluído</option>
      </select>

      <button type="submit">Salvar Ordem</button>
    </form>
  </div>

  <!-- Script para ativar automaticamente o menu ativo -->
  <script>
    const links = document.querySelectorAll('.sidebar .menu li a');
    const currentPage = window.location.pathname.split('/').pop();
    links.forEach(link => {
      if (link.getAttribute('href') === currentPage) {
        link.classList.add('active');
      }
    });
  </script>

  <!-- Script para validar valor positivo -->
  <script>
    const form = document.querySelector('form');
    const valorInput = document.getElementById('valor');

    form.addEventListener('submit', function(event) {
      const valor = parseFloat(valorInput.value);
      if (isNaN(valor) || valor < 0) {
        alert("O valor não pode ser negativo.");
        event.preventDefault();
      }
    });
  </script>

  <!-- Máscara CPF -->
  <script>
    document.getElementById('cpfcliente').addEventListener('input', function (e) {
      let value = e.target.value.replace(/\D/g, "");
      if (value.length > 11) value = value.slice(0, 11);

      value = value.replace(/(\d{3})(\d)/, "$1.$2");
      value = value.replace(/(\d{3})(\d)/, "$1.$2");
      value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

      e.target.value = value;
    });
  </script>
</body>
</html>
