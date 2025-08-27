<?php 
session_start();
require_once 'conexao.php';

// VERIFICA SE O USUARIO TEM PERMISSAO
if (!isset($_SESSION['cargo']) || ($_SESSION['cargo'] != "Gerente")) {
    header("Location: dashboard.php");
    exit();
}

// =================== CADASTRAR NOVO USUÁRIO OU CLIENTE ===================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $nome  = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $cargo = $_POST['cargo'] ?? '';
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    if (!empty($nome) && !empty($email) && !empty($cargo)) {
        if ($cargo === "Cliente") {
            $telefone = $_POST['telefone'] ?? '';
            $endereco = $_POST['endereco'] ?? '';
            $cpf      = $_POST['cpf'] ?? '';

            $sql = "INSERT INTO cliente (cpf, nome, email, telefone, endereco) 
                    VALUES (:cpf, :nome, :email, :telefone, :endereco)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':endereco', $endereco);

            if ($stmt->execute()) {
                echo "<script>alert('Cliente cadastrado com sucesso!'); window.location.href='usuarios.php?filtro_cargo=Cliente';</script>";
                exit;
            } else {
                echo "<script>alert('Erro ao cadastrar cliente!');</script>";
            }
        } else {
            if (!empty($senha)) {
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
                echo "<script>alert('Preencha todos os campos obrigatórios!');</script>";
            }
        }
    } else {
        echo "<script>alert('Preencha todos os campos!');</script>";
    }
}

// =================== BUSCAR USUÁRIOS OU CLIENTES ===================
$filtroCargo = isset($_GET['filtro_cargo']) ? $_GET['filtro_cargo'] : '';
if ($filtroCargo === 'Cliente') {
    $sqlUsuarios = "SELECT cpf, nome AS nome_completo, email, 'Cliente' AS cargo, 1 AS ativo, telefone, endereco 
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

        <!-- Inputs extras aparecem só quando for Cliente -->
        <input type="text" id="filtro-cpf" placeholder="CPF" class="filtro-input" style="display:none;">
        <input type="text" id="filtro-telefone" placeholder="Telefone" class="filtro-input" style="display:none;">

        <select id="filtro-cargo" class="filtro-input" onchange="atualizarFiltro()">
            <option value="">Todos os Cargos</option>
            <option value="Gerente" <?php if($filtroCargo==='Gerente') echo 'selected'; ?>>Gerente</option>
            <option value="Atendente" <?php if($filtroCargo==='Atendente') echo 'selected'; ?>>Atendente</option>
            <option value="Tecnico" <?php if($filtroCargo==='Tecnico') echo 'selected'; ?>>Técnico</option>
            <option value="Cliente" <?php if($filtroCargo==='Cliente') echo 'selected'; ?>>Cliente</option>
        </select>
      </div>
    </div>

    <!-- Adicionar Usuário -->
    <div class="add-usuario">
      <h2>➕ Adicionar Novo Usuário/Cliente</h2>
      <form class="form-adicionar" method="POST" action="">
        <input type="hidden" name="acao" value="adicionar">
        <input type="text" name="nome" placeholder="Nome" required />
        <input type="email" name="email" placeholder="Email" required />

        <!-- Campos extras para Cliente -->
        <div id="campos-cliente" style="display:none;">
          <input type="text" name="cpf" placeholder="CPF do cliente" />
          <input type="text" name="telefone" placeholder="Telefone do cliente" />
          <input type="text" name="endereco" placeholder="Endereço do cliente" />
        </div>

        <!-- Senha somente para usuários -->
        <input type="password" name="senha" placeholder="Senha" id="campo-senha" />

        <select name="cargo" id="cargo" required onchange="toggleCampos()">
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
      <h2>Usuários/Clientes Cadastrados</h2>
      <table class="usuarios-table">
  <thead>
    <tr>
      <th>Nome</th>
      <th>Email</th>
      <th>Cargo</th>
      <th>Status</th>
      <?php if ($filtroCargo === 'Cliente'): ?>
        <th>Telefone</th>
        <th>Endereço</th>
        <th>CPF</th>
      <?php endif; ?>
      <th>Ações</th>
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
          <?php if ($filtroCargo === 'Cliente'): ?>
            <td><?php echo htmlspecialchars($u['telefone'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($u['endereco'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($u['cpf'] ?? 'Não informado'); ?></td>
          <?php endif; ?>
          <td>
            <button class="editar">✏️</button>
            <button class="excluir">🗑️</button>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="<?php echo $filtroCargo === 'Cliente' ? '8' : '5'; ?>">
          Nenhum usuário/cliente cadastrado.
        </td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>
    </div>
  </div>

<script>
function atualizarFiltro() {
  const cargo = document.getElementById("filtro-cargo").value;
  const cpfInput = document.getElementById("filtro-cpf");
  const telInput = document.getElementById("filtro-telefone");

  if (cargo === "Cliente") {
    cpfInput.style.display = "block";
    telInput.style.display = "block";
  } else {
    cpfInput.style.display = "none";
    telInput.style.display = "none";
    cpfInput.value = "";
    telInput.value = "";
  }

  // Atualiza a URL
  window.location.href = "usuarios.php" + (cargo ? "?filtro_cargo=" + cargo : "");
}

document.querySelectorAll('.filtro-input').forEach(filtro => {
  filtro.addEventListener('input', function () {
    const nomeFiltro = document.getElementById('filtro-nome').value.toLowerCase();
    const emailFiltro = document.getElementById('filtro-email').value.toLowerCase();
    const cargoFiltro = document.getElementById('filtro-cargo').value.toLowerCase();
    const cpfFiltro   = document.getElementById('filtro-cpf').value.toLowerCase();
    const telFiltro   = document.getElementById('filtro-telefone').value.toLowerCase();

    const linhas = document.querySelectorAll('.usuarios-table tbody tr');

    linhas.forEach(linha => {
      const colunas = linha.querySelectorAll('td');
      const nome = colunas[0].textContent.toLowerCase();
      const email = colunas[1].textContent.toLowerCase();
      const cargo = colunas[2].textContent.toLowerCase();

      let corresponde = nome.includes(nomeFiltro) &&
                        email.includes(emailFiltro) &&
                        (cargoFiltro === '' || cargo.includes(cargoFiltro));

      // Se a linha for Cliente, verifica CPF e Telefone também
      if (cargo === "cliente") {
        const telefone = colunas[4]?.textContent.toLowerCase() || "";
        const endereco = colunas[5]?.textContent.toLowerCase() || "";
        const cpf = colunas[6]?.textContent.toLowerCase() || "";

        if (cpfFiltro && !cpf.includes(cpfFiltro)) corresponde = false;
        if (telFiltro && !telefone.includes(telFiltro)) corresponde = false;
      }

      linha.style.display = corresponde ? '' : 'none';
    });
  });
});

// Toggle campos do formulário
function toggleCampos() {
  const cargo = document.getElementById("cargo").value;
  const camposCliente = document.getElementById("campos-cliente");
  const campoSenha = document.getElementById("campo-senha");

  if (cargo === "Cliente") {
    camposCliente.style.display = "block";
    campoSenha.style.display = "none";
    campoSenha.removeAttribute("required");
  } else {
    camposCliente.style.display = "none";
    campoSenha.style.display = "block";
    campoSenha.setAttribute("required", "required");
  }
}

// Inicializa campos se filtro_cargo=Cliente
window.onload = function() {
  const filtroCargo = "<?php echo $filtroCargo; ?>";
  if(filtroCargo === "Cliente") {
    document.getElementById("filtro-cpf").style.display = "block";
    document.getElementById("filtro-telefone").style.display = "block";
    document.getElementById("cargo").value = "Cliente";
    toggleCampos();
  }
};
</script>

<style>
.inativo { opacity: 0.5; text-decoration: line-through; }
.filtro { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px; }
.filtro input, .filtro select { padding: 8px; font-size: 14px; border: 1px solid #ccc; border-radius: 5px; }
</style>

</body>
</html>
