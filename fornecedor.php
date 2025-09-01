<?php
session_start();
require_once 'conexao.php';

// VERIFICA SE O USUARIO TEM PERMISSAO
if (!isset($_SESSION['cargo']) || ($_SESSION['cargo'] != "Gerente" && $_SESSION['cargo'] != "Atendente")) {
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

// Processar formulário de cadastro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'cadastrar') {
    $nome = $_POST['nome_fornecedor'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    $sql = "INSERT INTO fornecedor (nome_fornecedor, telefone, email) VALUES (:nome_fornecedor, :telefone, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_fornecedor', $nome);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        echo "<script>alert('Fornecedor cadastrado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar o Fornecedor!');</script>";
    }
}

// Processar edição de fornecedor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'editar') {
    $id = $_POST['id_fornecedor'];
    $nome = $_POST['nome_fornecedor'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    $sql = "UPDATE fornecedor SET nome_fornecedor = :nome_fornecedor, telefone = :telefone, email = :email WHERE id_fornecedor = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nome_fornecedor', $nome);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        echo "<script>alert('Fornecedor atualizado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao atualizar o Fornecedor!');</script>";
    }
}

// Processar exclusão de fornecedor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'excluir') {
    $id = $_POST['id_fornecedor'];

    $sql = "DELETE FROM fornecedor WHERE id_fornecedor = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "<script>alert('Fornecedor excluído com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao excluir o Fornecedor!');</script>";
    }
}

// Buscar fornecedores do banco de dados
$sql = "SELECT id_fornecedor, nome_fornecedor, telefone, email FROM fornecedor";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cadastro de Fornecedor</title>
  <link rel="stylesheet" href="css/fornecedor.css" />
  <link rel="stylesheet" href="css/sidebar.css" />
  <link rel="stylesheet" href="css/form.css" />
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    /* Estilo para o modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 500px;
    }
    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }
    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }
    .action-buttons button {
      margin: 0 5px;
      padding: 5px 10px;
      cursor: pointer;
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
        <li><a href="<?php echo $item['href']; ?>"><?php echo $item['icon']; ?> <span><?php echo $item['text']; ?></span></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <!-- Conteúdo principal -->
  <main class="content">
    <div class="form-container">
      <h2>Cadastro de Fornecedor</h2>
      <form action="fornecedor.php" method="POST">
        <input type="hidden" name="action" value="cadastrar">
        <label for="nome_fornecedor">Nome completo:</label>
        <input type="text" id="nome_fornecedor" name="nome_fornecedor" required />

        <label for="telefone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone" required />

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required />

        <div class="form-buttons">
          <button type="submit">Cadastrar</button>
          <button type="button" id="cancelar">Cancelar</button>
        </div>
      </form>
    </div>
  </main>

  <h2>Lista de Fornecedores</h2>
  <div class="tabela-fornecedor">
    <table class="fornecedor-table">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Telefone</th>
          <th>Email</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($fornecedores) > 0): ?>
          <?php foreach ($fornecedores as $fornecedor): ?>
            <tr>
              <td><?php echo htmlspecialchars($fornecedor['nome_fornecedor']); ?></td>
              <td><?php echo htmlspecialchars($fornecedor['telefone']); ?></td>
              <td><?php echo htmlspecialchars($fornecedor['email']); ?></td>
              <td class="action-buttons">
                <button onclick="openEditModal(<?php echo $fornecedor['id_fornecedor']; ?>, '<?php echo htmlspecialchars($fornecedor['nome_fornecedor']); ?>', '<?php echo htmlspecialchars($fornecedor['telefone']); ?>', '<?php echo htmlspecialchars($fornecedor['email']); ?>')">Editar</button>
                <form action="fornecedor.php" method="POST" style="display:inline;">
                  <input type="hidden" name="action" value="excluir">
                  <input type="hidden" name="id_fornecedor" value="<?php echo $fornecedor['id_fornecedor']; ?>">
                  <button type="submit" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">Excluir</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="4">Nenhum fornecedor cadastrado.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Modal para edição -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeEditModal()">&times;</span>
      <h2>Editar Fornecedor</h2>
      <form action="fornecedor.php" method="POST">
        <input type="hidden" name="action" value="editar">
        <input type="hidden" name="id_fornecedor" id="edit_id_fornecedor">
        <label for="edit_nome_fornecedor">Nome completo:</label>
        <input type="text" id="edit_nome_fornecedor" name="nome_fornecedor" required />
        <label for="edit_telefone">Telefone:</label>
        <input type="tel" id="edit_telefone" name="telefone" required />
        <label for="edit_email">E-mail:</label>
        <input type="email" id="edit_email" name="email" required />
        <div class="form-buttons">
          <button type="submit">Salvar</button>
          <button type="button" onclick="closeEditModal()">Cancelar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    // Ativa o menu da página atual
    const links = document.querySelectorAll('.sidebar .menu li a');
    const currentPage = window.location.pathname.split('/').pop();
    links.forEach(link => {
      if (link.getAttribute('href') === currentPage) {
        link.classList.add('active');
      }
    });

    // Funções para o modal
    function openEditModal(id, nome, telefone, email) {
      document.getElementById('edit_id_fornecedor').value = id;
      document.getElementById('edit_nome_fornecedor').value = nome;
      document.getElementById('edit_telefone').value = telefone;
      document.getElementById('edit_email').value = email;
      document.getElementById('editModal').style.display = 'block';
    }

    function closeEditModal() {
      document.getElementById('editModal').style.display = 'none';
    }

    // Fechar o modal ao clicar fora dele
    window.onclick = function(event) {
      const modal = document.getElementById('editModal');
      if (event.target == modal) {
        closeEditModal();
      }
    }
  </script>

  <!-- Máscaras de entrada -->
  <script src="https://cdn.jsdelivr.net/npm/inputmask/dist/inputmask.min.js"></script>
  <script>
    Inputmask({ mask: "(99) 99999-9999" }).mask("#telefone");
    Inputmask({ mask: "(99) 99999-9999" }).mask("#edit_telefone");
  </script>

  <!-- Script do botão cancelar -->
  <script>
    document.getElementById("cancelar").addEventListener("click", () => {
      const form = document.querySelector("form");
      form.reset(); // limpa todos os campos
    });
  </script>

  <!-- Remover script de CPF (não usado no formulário de fornecedor) -->
</body>
</html>