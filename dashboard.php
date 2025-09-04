<?php
session_start(); // Inicia sessão
require_once 'conexao.php'; // Importa conexão com banco

// Verifica se usuário está logado
if (!isset($_SESSION['cargo'])) {
    header("Location: login.php"); // Se não estiver logado → volta para login
    exit();
}

// Definição dos menus para cada cargo
$menus = [
    'Gerente' => [
        ['href' => 'dashboard.php', 'icon' => '👤', 'text' => 'Perfil'],
        ['href' => 'cadastro-cliente.php', 'icon' => '📋', 'text' => 'Cadastro Cliente'],
        ['href' => 'cadastro-ordem_serv.php', 'icon' => '🛠️', 'text' => 'Cadastro Ordem de Serviço'],
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
        ['href' => 'cadastro-ordem_serv.php', 'icon' => '🛠️', 'text' => 'Cadastro Ordem de Serviço'],
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

// Pega o menu do cargo logado
$menuItems = isset($_SESSION['cargo']) && isset($menus[$_SESSION['cargo']]) ? $menus[$_SESSION['cargo']] : [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
  <!-- Sidebar -->
  <nav class="sidebar">
    <div class="logo">
      <img src="img/logo.png" alt="Logo">
    </div>
    <ul class="menu">
      <?php foreach ($menuItems as $item): ?>
        <li><a href="<?= $item['href'] ?>"><?= $item['icon'] ?> <span><?= $item['text'] ?></span></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <!-- Conteúdo principal -->
  <div class="dashboard-container">
    <h2>Bem-vindo, <?= $_SESSION['cargo']; ?>!</h2>
    <p>Use o menu lateral para navegar pelo sistema.</p>
  </div>
</body>
</html>
