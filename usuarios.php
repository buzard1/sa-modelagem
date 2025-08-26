<?php 
session_start();
require_once 'conexao.php';

// VERIFICA SE O USUARIO TEM PERMISSAO
if (!isset($_SESSION['cargo']) || ($_SESSION['cargo'] != "Gerente")) {
    echo "Acesso Negado!";
    header("Location: dashboard.php");
    exit();
}

// =================== CADASTRAR NOVO USUÁRIO ===================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $nome  = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $cargo = $_POST['cargo'] ?? '';
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    if (!empty($nome) && !empty($email) && !empty($senha) && !empty($cargo)) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (nome_completo, email, senha, cargo, ativo) 
                VALUES (:nome, :email, :senha, :cargo, :ativo)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senhaHash);
        $stmt->bindParam(':cargo', $cargo);
        $stmt->bindParam(':ativo', $ativo);

        if ($stmt->execute()) {
            echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href='usuarios.php';</script>";
            exit;
        } else {
            echo "<script>alert('Erro ao cadastrar usuário!');</script>";
        }
    } else {
        echo "<script>alert('Preencha todos os campos!');</script>";
    }
}

// =================== BUSCAR USUÁRIOS OU CLIENTES ===================
$filtroCargo = isset($_GET['filtro_cargo']) ? $_GET['filtro_cargo'] : '';
if ($filtroCargo === 'Cliente') {
    $sqlUsuarios = "SELECT cpf AS id_usuario, nome AS nome_completo, email, 'Cliente' AS cargo, 1 AS ativo, telefone, endereco 
                    FROM cliente ORDER BY cpf DESC";
} else {
    $sqlUsuarios = "SELECT id_usuario, nome_completo, email, cargo, ativo FROM usuario ORDER BY id_usuario DESC";
}
$stmtUsuarios = $pdo->query($sqlUsuarios);
$usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

// =================== MENU ===================
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
];
$menuItems = isset($_SESSION['cargo']) && isset($menus[$_SESSION['cargo']]) ? $menus[$_SESSION['cargo']] : [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gerenciar Usuários</title>
  <link rel="stylesheet" href="css/usuarios.css" />
  <link rel="stylesheet" href="css/sidebar.css" />
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

  <div class="usuarios-container">
    <h1>👥 Gerenciar Usuários</h1>

    <!-- Filtro de Usuários -->
    <div class="filtro-container">
      <h2>🔍 Buscar Usuários</h2>
      <div class="filtro">
        <input type="text" id="filtro-nome" placeholder="Nome" class="filtro-input">
        <input type="text" id="filtro-email" placeholder="Email" class="filtro-input">
        <select id="filtro-cargo" class="filtro-input" onchange="window.location.href='usuarios.php?filtro_cargo='+this.value">
          <option value="">Todos os Cargos</option>
          <option value="Gerente">Gerente</option>
          <option value="Atendente">Atendente</option>
          <option value="Tecnico">Técnico</option>
          <option value="Cliente">Cliente</option>
        </select>
      </div>
    </div>

    <!-- Adicionar Usuário -->
    <div class="add-usuario">
      <h2>➕ Adicionar Novo Usuário</h2>
      <form class="form-adicionar" method="POST" action="">
        <input type="hidden" name="acao" value="adicionar">
        <input type="text" name="nome" placeholder="Nome do usuário" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="senha" placeholder="Senha" required />
        <select name="cargo" required>
          <option value="Gerente">Gerente</option>
          <option value="Atendente">Atendente</option>
          <option value="Tecnico">Técnico</option>
          <option value="Cliente">Cliente</option>
        </select>
        <label>
          <input type="checkbox" name="ativo" checked> Ativo
        </label>
        <button type="submit">➕ Adicionar</button>
      </form>
    </div>

    <!-- Tabela de Usuários -->
    <div class="tabela-usuarios">
      <h2>Usuários Cadastrados</h2>
      <table class="usuarios-table">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Cargo</th>
            <th>Status</th>
            <th>Ações</th>
            <?php if ($filtroCargo === 'Cliente'): ?>
              <th>Telefone</th>
              <th>Endereço</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php if (count($usuarios) > 0): ?>
            <?php foreach ($usuarios as $u): ?>
              <tr class="<?php echo $u['ativo'] ? '' : 'inativo'; ?>">
                <td><?php echo htmlspecialchars($u['nome_completo']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo htmlspecialchars($u['cargo']); ?></td>
                <td><?php echo $u['ativo'] ? 'Ativo' : 'Inativo'; ?></td>
                <td>
                  <button class="editar">✏️</button>
                  <button class="excluir">🗑️</button>
                </td>
                <?php if ($filtroCargo === 'Cliente'): ?>
                  <td><?php echo htmlspecialchars($u['telefone']); ?></td>
                  <td><?php echo htmlspecialchars($u['endereco']); ?></td>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5">Nenhum usuário cadastrado.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Script do filtro -->
  <script>
    document.querySelectorAll('.filtro-input').forEach(filtro => {
      filtro.addEventListener('input', function () {
        const nomeFiltro = document.getElementById('filtro-nome').value.toLowerCase();
        const emailFiltro = document.getElementById('filtro-email').value.toLowerCase();
        const cargoFiltro = document.getElementById('filtro-cargo').value.toLowerCase();

        const linhas = document.querySelectorAll('.usuarios-table tbody tr');

        linhas.forEach(linha => {
          const colunas = linha.querySelectorAll('td');
          const nome = colunas[0].textContent.toLowerCase();
          const email = colunas[1].textContent.toLowerCase();
          const cargo = colunas[2].textContent.toLowerCase();

          const corresponde = nome.includes(nomeFiltro) &&
                              email.includes(emailFiltro) &&
                              (cargoFiltro === '' || cargo.includes(cargoFiltro));

          linha.style.display = corresponde ? '' : 'none';
        });
      });
    });
  </script>

  <style>
    .inativo {
      opacity: 0.5;
      text-decoration: line-through;
    }
    .filtro {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 15px;
    }
    .filtro input,
    .filtro select {
      padding: 8px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
  </style>
</body>
</html>