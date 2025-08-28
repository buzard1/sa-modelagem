<?php 
session_start();
require_once 'conexao.php';

// VERIFICA SE O USUARIO TEM PERMISSAO
if (!isset($_SESSION['cargo']) || ($_SESSION['cargo'] != "Gerente" && $_SESSION['cargo'] != "Atendente" && $_SESSION['cargo'] != "Tecnico")) {
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

// PROCESSA O FORMULÁRIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = urlencode($_POST['nome']);
    $email = urlencode($_POST['email']);
    $problema = urlencode($_POST['problema']);
    $urgencia = urlencode($_POST['urgencia']);

    $destinatario = "tropadoingui@gmail.com"; 
    $assunto = "Chamado de Suporte - Urgência: $urgencia";
    $corpo = "Nome: $nome%0AEmail: $email%0AUrgência: $urgencia%0AProblema:%0A$problema";

    // Abre o cliente de email do usuário
    header("Location: mailto:$destinatario?subject=$assunto&body=$corpo");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Suporte</title>
  <link rel="stylesheet" href="css/sidebar.css" />
  <link rel="stylesheet" href="css/suporte.css" />
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
    <h2>🛠️ Suporte Técnico</h2>

    <p class="descricao">
      Está com problemas? Preencha o formulário abaixo e nossa equipe entrará em contato.
    </p>

    <form method="POST" action="">
      <label for="nome">Nome:</label>
      <input type="text" id="nome" name="nome" required />

      <label for="email">E-mail:</label>
      <input type="email" id="email" name="email" required />

      <label for="problema">Descrição do Problema:</label>
      <textarea id="problema" name="problema" rows="5" required placeholder="Descreva com detalhes..."></textarea>

      <label for="urgencia">Urgência:</label>
      <select id="urgencia" name="urgencia" required>
        <option value="baixa">Baixa</option>
        <option value="media">Média</option>
        <option value="alta">Alta</option>
      </select>

      <button type="submit">Enviar</button>
    </form>

    <p class="contato">📧 Ou envie um e-mail diretamente para <strong>saolonguinho@gmail.com</strong></p>
  </div>
  <!-- Script: ativa o menu da página atual -->
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
