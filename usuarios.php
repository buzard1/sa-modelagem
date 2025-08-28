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
<!-- Modal for Editing User/Client -->
<div id="editModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>Editar Usuário/Cliente</h2>
    <form id="editForm" method="POST" action="">
      <input type="hidden" name="acao" value="editar">
      <input type="hidden" name="id" id="edit-id">
      <input type="text" name="nome" id="edit-nome" placeholder="Nome" required />
      <input type="email" name="email" id="edit-email" placeholder="Email" required />
      
      <!-- Campos extras para Cliente -->
      <div id="edit-campos-cliente" style="display:none;">
        <input type="text" name="cpf" id="edit-cpf" placeholder="CPF do cliente" />
        <input type="text" name="telefone" id="edit-telefone" placeholder="Telefone do cliente" />
        <input type="text" name="endereco" id="edit-endereco" placeholder="Endereço do cliente" />
      </div>
      
      <!-- Senha somente para usuários -->
      <input type="password" name="senha" id="edit-senha" placeholder="Nova senha (opcional)" />
      
      <select name="cargo" id="edit-cargo" required onchange="toggleEditCampos()">
        <option value="Gerente">Gerente</option>
        <option value="Atendente">Atendente</option>
        <option value="Tecnico">Técnico</option>
        <option value="Cliente">Cliente</option>
      </select>
      
      <label id="ativo-container">
        <input type="checkbox" name="ativo" id="edit-ativo"> Ativo
      </label>
      <button type="submit">Salvar Alterações</button>
    </form>
  </div>
</div>
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
  /* ---------- helpers ---------- */
function normalizar(str) {
  return String(str || '').normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
}

/* ---------- estado ---------- */
let currentCargo = "<?php echo addslashes($filtroCargo); ?>"; // cargo que está sendo exibido no momento

/* ---------- fetch + render ---------- */
async function fetchAndRender(cargo) {
  try {
    const url = 'usuarios_ajax.php' + (cargo ? '?filtro_cargo=' + encodeURIComponent(cargo) : '');
    const res = await fetch(url);
    if (!res.ok) throw new Error('Erro ao buscar dados: ' + res.status);
    const json = await res.json();
    if (!json.success) throw new Error(json.error || 'Resposta inválida do servidor');

    renderTable(json.data, cargo || '');
  } catch (err) {
    console.error(err);
    alert('Erro ao buscar usuários. Veja console para detalhes.');
  }
}

function renderTable(data, cargo) {
  const table = document.querySelector('.usuarios-table');
  const thead = table.querySelector('thead');
  const tbody = table.querySelector('tbody');

  // Cabeçalho dinâmico
  if (cargo === 'Cliente') {
    thead.innerHTML = `
      <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Cargo</th>
        <th>Status</th>
        <th>Telefone</th>
        <th>Endereço</th>
        <th>CPF</th>
        <th>Ações</th>
      </tr>
    `;
  } else {
    thead.innerHTML = `
      <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Cargo</th>
        <th>Status</th>
        <th>Ações</th>
      </tr>
    `;
  }

  // Linhas
  tbody.innerHTML = '';
  data.forEach(u => {
    const tr = document.createElement('tr');
    if (!Number(u.ativo) || u.ativo == 0) tr.classList.add('inativo');

    const tdNome = document.createElement('td'); tdNome.textContent = u.nome_completo ?? u.nome ?? '';
    const tdEmail = document.createElement('td'); tdEmail.textContent = u.email ?? '';
    const tdCargo = document.createElement('td'); tdCargo.textContent = u.cargo ?? '';
    const tdStatus = document.createElement('td'); tdStatus.textContent = (Number(u.ativo) ? 'Ativo' : 'Inativo');

    tr.appendChild(tdNome);
    tr.appendChild(tdEmail);
    tr.appendChild(tdCargo);
    tr.appendChild(tdStatus);

    if (cargo === 'Cliente') {
      const tdTel = document.createElement('td'); tdTel.textContent = u.telefone ?? '';
      const tdEnd = document.createElement('td'); tdEnd.textContent = u.endereco ?? '';
      const tdCpf = document.createElement('td'); tdCpf.textContent = u.cpf ?? 'Não informado';
      tr.appendChild(tdTel);
      tr.appendChild(tdEnd);
      tr.appendChild(tdCpf);
    }

    // Ações
    const tdActions = document.createElement('td');
    const btnEdit = document.createElement('button'); btnEdit.className = 'editar'; btnEdit.type = 'button'; btnEdit.textContent = '✏️';
    const btnDel = document.createElement('button'); btnDel.className = 'excluir'; btnDel.type = 'button'; btnDel.textContent = '🗑️';
    
    // Store data in button for edit/delete
    btnEdit.dataset.id = cargo === 'Cliente' ? u.cpf : u.id_usuario;
    btnEdit.dataset.cargo = u.cargo;
    btnEdit.dataset.nome = u.nome_completo ?? u.nome;
    btnEdit.dataset.email = u.email;
    btnEdit.dataset.ativo = u.ativo;
    if (cargo === 'Cliente') {
      btnEdit.dataset.cpf = u.cpf;
      btnEdit.dataset.telefone = u.telefone;
      btnEdit.dataset.endereco = u.endereco;
    }
    
    btnDel.dataset.id = cargo === 'Cliente' ? u.cpf : u.id_usuario;
    btnDel.dataset.cargo = u.cargo;

    btnEdit.addEventListener('click', handleEdit);
    btnDel.addEventListener('click', handleDelete);
    
    tdActions.appendChild(btnEdit);
    tdActions.appendChild(btnDel);
    tr.appendChild(tdActions);

    tbody.appendChild(tr);
  });

  currentCargo = cargo || '';
  applyFilters(); // Reaplica os filtros visuais após renderizar
}

/* ---------- filtro (aplica nas linhas já renderizadas) ---------- */
function applyFilters() {
  const nomeFiltro = normalizar(document.getElementById('filtro-nome').value);
  const emailFiltro = normalizar(document.getElementById('filtro-email').value);
  const cargoFiltro = normalizar(document.getElementById('filtro-cargo').value);
  const cpfFiltro = normalizar(document.getElementById('filtro-cpf').value);
  const telFiltro = normalizar(document.getElementById('filtro-telefone').value);

  const linhas = document.querySelectorAll('.usuarios-table tbody tr');

  linhas.forEach(linha => {
    const colunas = linha.querySelectorAll('td');
    const nome = normalizar(colunas[0]?.textContent || '');
    const email = normalizar(colunas[1]?.textContent || '');
    const cargo = normalizar(colunas[2]?.textContent || '');

    let corresponde = nome.includes(nomeFiltro) &&
                      email.includes(emailFiltro) &&
                      (cargoFiltro === '' || cargo.includes(cargoFiltro));

    if (currentCargo === 'Cliente') {
      const telefone = normalizar(colunas[4]?.textContent || '');
      const cpf = normalizar(colunas[6]?.textContent || '');
      if (cpfFiltro && !cpf.includes(cpfFiltro)) corresponde = false;
      if (telFiltro && !telefone.includes(telFiltro)) corresponde = false;
    }

    linha.style.display = corresponde ? '' : 'none';
  });
}

/* ---------- atualizarFiltro (chama fetch sem recarregar a página) ---------- */
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

  fetchAndRender(cargo);
}

/* ---------- toggle campos do formulário de criação ---------- */
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

/* ---------- toggle campos do formulário de edição ---------- */
function toggleEditCampos() {
  const cargo = document.getElementById("edit-cargo").value;
  const camposCliente = document.getElementById("edit-campos-cliente");
  const campoSenha = document.getElementById("edit-senha");
  const ativoContainer = document.getElementById("ativo-container");

  if (cargo === "Cliente") {
    camposCliente.style.display = "block";
    campoSenha.style.display = "none";
    campoSenha.removeAttribute("required");
    ativoContainer.style.display = "none"; // Cliente é sempre ativo
  } else {
    camposCliente.style.display = "none";
    campoSenha.style.display = "block";
    campoSenha.removeAttribute("required"); // Senha é opcional na edição
    ativoContainer.style.display = "block";
  }
}

/* ---------- abrir/fechar modal de edição ---------- */
function openModal() {
  document.getElementById('editModal').style.display = 'flex';
}

function closeModal() {
  document.getElementById('editModal').style.display = 'none';
}

/* ---------- handler para botão de editar ---------- */
function handleEdit(event) {
  const button = event.target;
  const id = button.dataset.id;
  const cargo = button.dataset.cargo;
  const nome = button.dataset.nome;
  const email = button.dataset.email;
  const ativo = button.dataset.ativo;
  const cpf = button.dataset.cpf;
  const telefone = button.dataset.telefone;
  const endereco = button.dataset.endereco;

  // Preenche o formulário de edição
  document.getElementById('edit-id').value = id;
  document.getElementById('edit-nome').value = nome;
  document.getElementById('edit-email').value = email;
  document.getElementById('edit-cargo').value = cargo;
  document.getElementById('edit-ativo').checked = Number(ativo) === 1;

  if (cargo === 'Cliente') {
    document.getElementById('edit-cpf').value = cpf || '';
    document.getElementById('edit-telefone').value = telefone || '';
    document.getElementById('edit-endereco').value = endereco || '';
  }

  toggleEditCampos();
  openModal();
}

/* ---------- handler para botão de excluir ---------- */
async function handleDelete(event) {
  const button = event.target;
  const id = button.dataset.id;
  const cargo = button.dataset.cargo;

  if (!confirm(`Tem certeza que deseja excluir este ${cargo === 'Cliente' ? 'cliente' : 'usuário'}?`)) {
    return;
  }

  try {
    const res = await fetch('usuarios_ajax.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ acao: 'excluir', id, cargo })
    });
    const json = await res.json();

    if (json.success) {
      alert(`${cargo === 'Cliente' ? 'Cliente' : 'Usuário'} excluído com sucesso!`);
      fetchAndRender(currentCargo); // Atualiza a tabela
    } else {
      alert(json.error || 'Erro ao excluir.');
    }
  } catch (err) {
    console.error(err);
    alert('Erro ao excluir. Veja console para detalhes.');
  }
}

/* ---------- submit do formulário de edição ---------- */
document.getElementById('editForm').addEventListener('submit', async function(event) {
  event.preventDefault();
  const formData = new FormData(this);
  const data = Object.fromEntries(formData);

  try {
    const res = await fetch('usuarios_ajax.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    const json = await res.json();

    if (json.success) {
      alert(`${data.cargo === 'Cliente' ? 'Cliente' : 'Usuário'} atualizado com sucesso!`);
      closeModal();
      fetchAndRender(currentCargo); // Atualiza a tabela
    } else {
      alert(json.error || 'Erro ao atualizar.');
    }
  } catch (err) {
    console.error(err);
    alert('Erro ao atualizar. Veja console para detalhes.');
  }
});

/* ---------- listeners de filtro ---------- */
document.querySelectorAll('.filtro-input').forEach(filtro => {
  filtro.addEventListener('input', applyFilters);
});

/* ---------- inicialização ---------- */
window.addEventListener('load', function() {
  const initialCargo = "<?php echo addslashes($filtroCargo); ?>";
  const selectCargo = document.getElementById('filtro-cargo');
  if (initialCargo && selectCargo) selectCargo.value = initialCargo;

  if (initialCargo === "Cliente") {
    document.getElementById("filtro-cpf").style.display = "block";
    document.getElementById("filtro-telefone").style.display = "block";
    document.getElementById("cargo").value = "Cliente";
    toggleCampos();
  }

  fetchAndRender(initialCargo);
});
</script>
<style>
.inativo { opacity: 0.5; text-decoration: line-through; }
.filtro { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px; }
.filtro input, .filtro select { padding: 8px; font-size: 14px; border: 1px solid #ccc; border-radius: 5px; }
</style>

</body>
</html>