<?php
session_start();
require_once 'conexao.php'; // conexão PDO

// VERIFICA SE O USUARIO TEM PERMISSAO
if (!isset($_SESSION['cargo']) || ($_SESSION['cargo'] != "Gerente")) {
    header("Location: dashboard.php");
    exit();
}

$mensagem = "";

// =================== CADASTRAR NOVO USUÁRIO OU CLIENTE ===================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $cargo = $_POST['cargo'] ?? '';
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    if (!empty($nome) && !empty($email) && !empty($cargo)) {
        try {
            if ($cargo === "Cliente") {
                $telefone = trim($_POST['telefone'] ?? '');
                $endereco = trim($_POST['endereco'] ?? '');
                $cpf      = trim($_POST['cpf'] ?? '');

                $sql = "INSERT INTO cliente (cpf, nome, email, telefone, endereco) 
                        VALUES (:cpf, :nome, :email, :telefone, :endereco)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':cpf', $cpf);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':telefone', $telefone);
                $stmt->bindParam(':endereco', $endereco);

                if ($stmt->execute()) {
                    $mensagem = "Cliente cadastrado com sucesso!";
                } else {
                    $mensagem = "Erro ao cadastrar cliente!";
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
                        $mensagem = "Usuário cadastrado com sucesso!";
                    } else {
                        $mensagem = "Erro ao cadastrar usuário!";
                    }
                } else {
                    $mensagem = "Preencha todos os campos obrigatórios!";
                }
            }
        } catch (PDOException $e) {
            $mensagem = "Erro ao cadastrar: " . $e->getMessage();
        }
    } else {
        $mensagem = "Preencha todos os campos!";
    }
}

// =================== EDITAR USUÁRIO OU CLIENTE ===================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['acao']) && $_POST['acao'] === 'editar') {
    $id = intval($_POST['id'] ?? 0);
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cargo = $_POST['cargo'] ?? '';
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    if (!empty($nome) && !empty($email) && !empty($cargo) && $id > 0) {
        try {
            if ($cargo === "Cliente") {
                $telefone = trim($_POST['telefone'] ?? '');
                $endereco = trim($_POST['endereco'] ?? '');
                $cpf      = trim($_POST['cpf'] ?? '');

                $sql = "UPDATE cliente SET cpf = :cpf, nome = :nome, email = :email, telefone = :telefone, endereco = :endereco 
                        WHERE cpf = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':cpf', $cpf);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':telefone', $telefone);
                $stmt->bindParam(':endereco', $endereco);
                $stmt->bindParam(':id', $id);

                if ($stmt->execute()) {
                    $mensagem = "Cliente atualizado com sucesso!";
                } else {
                    $mensagem = "Erro ao atualizar cliente!";
                }
            } else {
                $senha = $_POST['senha'] ?? '';
                $senhaHash = !empty($senha) ? password_hash($senha, PASSWORD_DEFAULT) : null;

                $sql = "UPDATE usuario SET nome_completo = :nome, email = :email, cargo = :cargo, ativo = :ativo";
                if ($senhaHash) {
                    $sql .= ", senha = :senha";
                }
                $sql .= " WHERE id_usuario = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':cargo', $cargo);
                $stmt->bindParam(':ativo', $ativo);
                $stmt->bindParam(':id', $id);
                if ($senhaHash) {
                    $stmt->bindParam(':senha', $senhaHash);
                }

                if ($stmt->execute()) {
                    $mensagem = "Usuário atualizado com sucesso!";
                } else {
                    $mensagem = "Erro ao atualizar usuário!";
                }
            }
        } catch (PDOException $e) {
            $mensagem = "Erro ao atualizar: " . $e->getMessage();
        }
    } else {
        $mensagem = "Preencha todos os campos!";
    }
}

// =================== EXCLUIR USUÁRIO OU CLIENTE ===================
if (isset($_GET['excluir']) && isset($_GET['tipo'])) {
    $id = intval($_GET['excluir']);
    $tipo = $_GET['tipo'];

    try {
        if ($tipo === 'cliente') {
            $sql = "DELETE FROM cliente WHERE cpf = :id";
        } else {
            $sql = "DELETE FROM usuario WHERE id_usuario = :id";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $mensagem = "Excluído com sucesso!";
        } else {
            $mensagem = "Erro ao excluir!";
        }
    } catch (PDOException $e) {
        $mensagem = "Erro ao excluir: " . $e->getMessage();
    }
}

// =================== BUSCAR USUÁRIOS OU CLIENTES ===================
$filtroCargo = isset($_GET['filtro_cargo']) ? $_GET['filtro_cargo'] : '';
if ($filtroCargo === 'Cliente') {
    $sqlUsuarios = "SELECT cpf AS id, nome AS nome_completo, email, 'Cliente' AS cargo, 1 AS ativo, telefone, endereco, cpf 
                    FROM cliente ORDER BY cpf DESC";
} else {
    $sqlUsuarios = "SELECT id_usuario AS id, nome_completo, email, cargo, ativo FROM usuario ORDER BY id_usuario DESC";
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
  <link rel="stylesheet" href="css/sidebar.css" />
  <link rel="stylesheet" href="css/form.css" />
  <link rel="icon" href="img/logo.png" type="image/png">
  <style>
    /* Estilos adaptados de pedidos.css */
    body {
      background-color: #121212;
      color: #e0e0e0;
      font-family: 'Segoe UI', sans-serif;
      padding: 2rem;
    }

    .usuarios-container {
      max-width: 1000px;
      margin: 0 auto;
      background-color: #1e1e1e;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.5);
    }

    h1, h2 {
      text-align: center;
      color: #03dac6;
      margin-bottom: 1.5rem;
    }

    .mensagem-sucesso {
      color: #66bb6a;
      text-align: center;
      margin-bottom: 1rem;
    }

    .mensagem-erro {
      color: #ff5252;
      text-align: center;
      margin-bottom: 1rem;
    }

    .usuarios-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 2rem;
    }

    .usuarios-table th,
    .usuarios-table td {
      padding: 1.2rem 1rem;
      text-align: left;
      vertical-align: middle;
      position: relative;
    }

    .usuarios-table th {
      background-color: #2a2a2a;
    }

    .usuarios-table tr {
      border-bottom: 1px solid transparent;
      position: relative;
    }

    .usuarios-table tr::after {
      content: '';
      position: absolute;
      top: 100%;
      left: 0;
      width: 100%;
      border-bottom: 1px solid #333;
      margin-top: 2px;
    }

    .actions {
      display: flex;
      gap: 0.5rem;
      position: relative;
      top: 3px;
      padding: 2px 0;
    }

    .action-btn {
      background-color: transparent;
      border: 1px solid #444;
      color: #fff;
      padding: 0.4rem;
      border-radius: 6px;
      cursor: pointer;
      font-size: 0.9rem;
      transition: all 0.3s;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      transform: translateY(1px);
    }

    .action-btn:hover {
      transform: scale(1.1) translateY(1px);
    }

    .edit-btn {
      border-color: #03dac6;
      color: #03dac6;
    }

    .action-btn.delete-btn {
      border: 2px solid #ff4444 !important;
    }

    .action-btn .tooltip-text {
      visibility: hidden;
      width: max-content;
      max-width: 150px;
      background-color: #333;
      color: #fff;
      text-align: center;
      padding: 6px 8px;
      border-radius: 6px;
      font-size: 12px;
      position: absolute;
      bottom: 125%;
      left: 50%;
      transform: translateX(-50%);
      opacity: 0;
      transition: opacity 0.3s ease;
      white-space: nowrap;
      pointer-events: none;
      z-index: 10;
    }

    .action-btn:hover .tooltip-text {
      visibility: visible;
      opacity: 1;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.8);
      overflow: auto;
    }

    .modal-content {
      background-color: #1e1e1e;
      color: #e0e0e0;
      margin: 2% auto;
      padding: 2rem;
      border-radius: 8px;
      width: 90%;
      max-width: 600px;
      position: relative;
      max-height: 90vh;
      overflow-y: auto;
    }

    .close-btn {
      position: absolute;
      right: 1rem;
      top: 1rem;
      font-size: 1.5rem;
      cursor: pointer;
      color: #888;
    }

    .close-btn:hover {
      color: #03dac6;
    }

    #modal-confirmacao .modal-content {
      background-color: #2e1e1e;
      border-top: 4px solid #ff4444;
    }

    #modal-confirmacao .btn-salvar {
      background-color: #ff4444 !important;
      color: white;
    }

    #modal-confirmacao .btn-salvar:hover {
      background-color: #cc0000 !important;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: bold;
      color: #bb86fc;
    }

    .form-input {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #444;
      border-radius: 4px;
      font-size: 1rem;
      background-color: #2b2b2b;
      color: #fff;
    }

    .form-input:focus {
      border-color: #03dac6;
      outline: none;
    }

    .form-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 1rem;
      margin-top: 1.5rem;
    }

    .btn-salvar {
      background-color: #03dac6;
      color: #000;
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .btn-salvar:hover {
      background-color: #00bfa5;
    }

    .btn-cancelar {
      background-color: #cf6679;
      color: #000;
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .btn-cancelar:hover {
      background-color: #b00020;
    }

    .inativo { opacity: 0.5; text-decoration: line-through; }
    .filtro { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px; }
    .filtro input, .filtro select { padding: 8px; font-size: 14px; border: 1px solid #ccc; border-radius: 5px; }

    @media (max-width: 600px) {
      .usuarios-table td {
        padding: 1rem 0.8rem;
      }

      .actions {
        top: 2px;
      }

      .modal-content {
        width: 95%;
        padding: 1rem;
      }

      .form-input {
        padding: 0.6rem;
      }

      .btn-salvar, .btn-cancelar {
        padding: 0.6rem 1rem;
      }
    }

    @media (max-height: 800px) {
      .modal-content {
        margin: 1% auto;
        padding: 1.5rem;
      }

      .form-group {
        margin-bottom: 0.8rem;
      }

      .form-buttons {
        margin-top: 1rem;
      }
    }
  </style>
  <script>
    let linhaEditando = null;

    function abrirModalEdicao(botao) {
      linhaEditando = botao.closest('tr');
      const modal = document.getElementById('modal-edicao');

      // Preencher o modal com os dados da linha
      document.getElementById('edit-nome').value = linhaEditando.cells[0].textContent;
      document.getElementById('edit-email').value = linhaEditando.cells[1].textContent;
      const cargo = linhaEditando.cells[2].textContent;
      document.getElementById('edit-cargo').value = cargo;
      document.getElementById('edit-ativo').checked = linhaEditando.cells[3].textContent === 'Ativo';
      const id = linhaEditando.getAttribute('data-id');
      document.getElementById('edit-id').value = id;
      const tipo = linhaEditando.getAttribute('data-tipo');
      document.getElementById('edit-tipo').value = tipo;

      toggleCamposEdicao(cargo);

      if (cargo === 'Cliente') {
        document.getElementById('edit-telefone').value = linhaEditando.cells[4].textContent;
        document.getElementById('edit-endereco').value = linhaEditando.cells[5].textContent;
        document.getElementById('edit-cpf').value = linhaEditando.cells[6].textContent;
        document.getElementById('campo-senha-edicao').style.display = 'none';
      } else {
        document.getElementById('campo-senha-edicao').style.display = 'block';
      }

      // Mostrar o modal
      modal.style.display = 'block';
    }

    function fecharModal() {
      document.getElementById('modal-edicao').style.display = 'none';
      linhaEditando = null;
    }

    function salvarEdicao() {
      if (!linhaEditando) return;

      // Obter os valores do formulário
      const nome = document.getElementById('edit-nome').value;
      const email = document.getElementById('edit-email').value;
      const cargo = document.getElementById('edit-cargo').value;
      const ativo = document.getElementById('edit-ativo').checked ? 1 : 0;
      const id = document.getElementById('edit-id').value;
      const tipo = document.getElementById('edit-tipo').value;
      const senha = document.getElementById('edit-senha').value;

      let body = `nome=${encodeURIComponent(nome)}&email=${encodeURIComponent(email)}&cargo=${cargo}&ativo=${ativo}&id=${id}&acao=editar`;

      if (cargo === 'Cliente') {
        const telefone = document.getElementById('edit-telefone').value;
        const endereco = document.getElementById('edit-endereco').value;
        const cpf = document.getElementById('edit-cpf').value;
        body += `&telefone=${encodeURIComponent(telefone)}&endereco=${encodeURIComponent(endereco)}&cpf=${encodeURIComponent(cpf)}`;
      } else if (senha) {
        body += `&senha=${encodeURIComponent(senha)}`;
      }

      // Enviar dados via AJAX
      fetch('usuarios.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: body
      })
      .then(response => response.text())
      .then(data => {
        // Atualizar a linha na tabela
        linhaEditando.cells[0].textContent = nome;
        linhaEditando.cells[1].textContent = email;
        linhaEditando.cells[2].textContent = cargo;
        linhaEditando.cells[3].textContent = ativo ? 'Ativo' : 'Inativo';
        if (cargo === 'Cliente') {
          linhaEditando.cells[4].textContent = telefone;
          linhaEditando.cells[5].textContent = endereco;
          linhaEditando.cells[6].textContent = cpf;
        }

        alert('Atualizado com sucesso!');
        fecharModal();
      })
      .catch(error => {
        alert('Erro ao salvar: ' + error);
      });
    }

    let linhaParaDeletar = null;

    function deletarLinha(botao) {
      linhaParaDeletar = botao.closest('tr');
      const nome = linhaParaDeletar.cells[0].textContent;

      document.getElementById('confirmacao-mensagem').textContent = 'Tem certeza que deseja deletar este usuário/cliente?';
      document.getElementById('confirmacao-detalhes').innerHTML = `<strong>Nome:</strong> ${nome}`;

      // Mostrar o modal de confirmação
      document.getElementById('modal-confirmacao').style.display = 'block';
    }

    function fecharModalConfirmacao() {
      document.getElementById('modal-confirmacao').style.display = 'none';
      linhaParaDeletar = null;
    }

    function confirmarExclusao() {
      if (!linhaParaDeletar) return;

      const confirmacaoFinal = prompt('Digite "DELETAR" para confirmar a exclusão:');
      if (confirmacaoFinal === 'DELETAR') {
        const id = linhaParaDeletar.getAttribute('data-id');
        const tipo = linhaParaDeletar.getAttribute('data-tipo');
        window.location.href = `usuarios.php?excluir=${id}&tipo=${tipo}`;
      } else {
        alert('Exclusão cancelada.');
        fecharModalConfirmacao();
      }
    }

    // Fechar o modal se clicar fora dele
    window.onclick = function(event) {
      const modalEdicao = document.getElementById('modal-edicao');
      const modalConfirmacao = document.getElementById('modal-confirmacao');
      if (event.target == modalEdicao) {
        fecharModal();
      }
      if (event.target == modalConfirmacao) {
        fecharModalConfirmacao();
      }
    };

    // Toggle campos do formulário de adicionar
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

    // Toggle campos do modal de edição
    function toggleCamposEdicao(cargo) {
      const camposCliente = document.getElementById("campos-cliente-edicao");
      const campoSenha = document.getElementById("campo-senha-edicao");

      if (cargo === "Cliente") {
        camposCliente.style.display = "block";
        campoSenha.style.display = "none";
      } else {
        camposCliente.style.display = "none";
        campoSenha.style.display = "block";
      }
    }

    // Inicializa campos se filtro_cargo=Cliente
    window.onload = function() {
      const filtroCargo = "<?php echo $filtroCargo; ?>";
      if (filtroCargo === "Cliente") {
        document.getElementById("filtro-cpf").style.display = "block";
        document.getElementById("filtro-telefone").style.display = "block";
        document.getElementById("cargo").value = "Cliente";
        toggleCampos();
      }
    };

    // Filtro dinâmico
    document.querySelectorAll('.filtro-input').forEach(filtro => {
      filtro.addEventListener('input', function () {
        const nomeFiltro = document.getElementById('filtro-nome').value.toLowerCase();
        const emailFiltro = document.getElementById('filtro-email').value.toLowerCase();
        const cargoFiltro = document.getElementById('filtro-cargo').value.toLowerCase();
        const cpfFiltro = document.getElementById('filtro-cpf').value.toLowerCase();
        const telFiltro = document.getElementById('filtro-telefone').value.toLowerCase();

        const linhas = document.querySelectorAll('.usuarios-table tbody tr');

        linhas.forEach(linha => {
          const colunas = linha.querySelectorAll('td');
          const nome = colunas[0].textContent.toLowerCase();
          const email = colunas[1].textContent.toLowerCase();
          const cargo = colunas[2].textContent.toLowerCase();

          let corresponde = nome.includes(nomeFiltro) &&
                            email.includes(emailFiltro) &&
                            (cargoFiltro === '' || cargo.includes(cargoFiltro));

          if (cargo === "cliente") {
            const telefone = colunas[4]?.textContent.toLowerCase() || "";
            const cpf = colunas[6]?.textContent.toLowerCase() || "";

            if (cpfFiltro && !cpf.includes(cpfFiltro)) corresponde = false;
            if (telFiltro && !telefone.includes(telFiltro)) corresponde = false;
          }

          linha.style.display = corresponde ? '' : 'none';
        });
      });
    });

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
  </script>
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

    <?php if ($mensagem): ?>
      <p class="<?= (strpos($mensagem, 'sucesso') !== false) ? 'mensagem-sucesso' : 'mensagem-erro' ?>">
        <?= htmlspecialchars($mensagem) ?>
      </p>
    <?php endif; ?>

    <!-- Filtro de Usuários -->
    <div class="filtro-container">
      <h2>🔍 Buscar Usuários</h2>
      <div class="filtro">
        <input type="text" id="filtro-nome" placeholder="Nome" class="form-input">
        <input type="text" id="filtro-email" placeholder="Email" class="form-input">

        <!-- Inputs extras aparecem só quando for Cliente -->
        <input type="text" id="filtro-cpf" placeholder="CPF" class="form-input" style="display:none;">
        <input type="text" id="filtro-telefone" placeholder="Telefone" class="form-input" style="display:none;">

        <select id="filtro-cargo" class="form-input" onchange="atualizarFiltro()">
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
        <div class="form-group">
          <label for="nome">Nome</label>
          <input type="text" name="nome" class="form-input" placeholder="Nome" required />
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" class="form-input" placeholder="Email" required />
        </div>

        <!-- Campos extras para Cliente -->
        <div id="campos-cliente" style="display:none;">
          <div class="form-group">
            <label for="cpf">CPF</label>
            <input type="text" name="cpf" class="form-input" placeholder="CPF do cliente" />
          </div>
          <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" class="form-input" placeholder="Telefone do cliente" />
          </div>
          <div class="form-group">
            <label for="endereco">Endereço</label>
            <input type="text" name="endereco" class="form-input" placeholder="Endereço do cliente" />
          </div>
        </div>

        <!-- Senha somente para usuários -->
        <div class="form-group" id="campo-senha-edicao">
          <label for="senha">Senha</label>
          <input type="password" name="senha" id="campo-senha" class="form-input" placeholder="Senha" />
        </div>

        <div class="form-group">
          <label for="cargo">Cargo</label>
          <select name="cargo" id="cargo" class="form-input" required onchange="toggleCampos()">
            <option value="Gerente">Gerente</option>
            <option value="Atendente">Atendente</option>
            <option value="Tecnico">Técnico</option>
            <option value="Cliente">Cliente</option>
          </select>
        </div>

        <div class="form-group">
          <label>
            <input type="checkbox" name="ativo" checked> Ativo
          </label>
        </div>
        <div class="form-buttons">
          <button type="submit" class="btn-salvar">➕ Adicionar</button>
        </div>
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
              <tr class="<?php echo $u['ativo'] ? '' : 'inativo'; ?>" data-id="<?= $u['id'] ?>" data-tipo="<?= $filtroCargo === 'Cliente' ? 'cliente' : 'usuario' ?>">
                <td><?= htmlspecialchars($u['nome_completo']); ?></td>
                <td><?= htmlspecialchars($u['email']); ?></td>
                <td><?= htmlspecialchars($u['cargo']); ?></td>
                <td><?= $u['ativo'] ? 'Ativo' : 'Inativo'; ?></td>
                <?php if ($filtroCargo === 'Cliente'): ?>
                  <td><?= htmlspecialchars($u['telefone'] ?? ''); ?></td>
                  <td><?= htmlspecialchars($u['endereco'] ?? ''); ?></td>
                  <td><?= htmlspecialchars($u['cpf'] ?? 'Não informado'); ?></td>
                <?php endif; ?>
                <td class="actions">
                  <button class="action-btn edit-btn" onclick="abrirModalEdicao(this)">✏️<span class="tooltip-text">Editar</span></button>
                  <button class="action-btn delete-btn" onclick="deletarLinha(this)">🗑️<span class="tooltip-text">Deletar</span></button>
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

  <!-- Modal de Edição -->
  <div id="modal-edicao" class="modal">
    <div class="modal-content">
      <span class="close-btn" onclick="fecharModal()">&times;</span>
      <h2 style="color: #03dac6;">Editar Usuário/Cliente</h2>
      <form id="form-edicao">
        <input type="hidden" id="edit-id" name="id" />
        <input type="hidden" id="edit-tipo" name="tipo" />
        <div class="form-group">
          <label for="edit-nome">Nome</label>
          <input type="text" id="edit-nome" class="form-input" required />
        </div>
        <div class="form-group">
          <label for="edit-email">Email</label>
          <input type="email" id="edit-email" class="form-input" required />
        </div>

        <!-- Campos extras para Cliente -->
        <div id="campos-cliente-edicao" style="display:none;">
          <div class="form-group">
            <label for="edit-cpf">CPF</label>
            <input type="text" id="edit-cpf" class="form-input" />
          </div>
          <div class="form-group">
            <label for="edit-telefone">Telefone</label>
            <input type="text" id="edit-telefone" class="form-input" />
          </div>
          <div class="form-group">
            <label for="edit-endereco">Endereço</label>
            <input type="text" id="edit-endereco" class="form-input" />
          </div>
        </div>

        <!-- Senha somente para usuários -->
        <div class="form-group" id="campo-senha-edicao">
          <label for="edit-senha">Nova Senha (opcional)</label>
          <input type="password" id="edit-senha" class="form-input" placeholder="Deixe em branco para não alterar" />
        </div>

        <div class="form-group">
          <label for="edit-cargo">Cargo</label>
          <select id="edit-cargo" class="form-input" required onchange="toggleCamposEdicao(this.value)">
            <option value="Gerente">Gerente</option>
            <option value="Atendente">Atendente</option>
            <option value="Tecnico">Técnico</option>
            <option value="Cliente">Cliente</option>
          </select>
        </div>

        <div class="form-group">
          <label>
            <input type="checkbox" id="edit-ativo" checked> Ativo
          </label>
        </div>
        <div class="form-buttons">
          <button type="button" class="btn-cancelar" onclick="fecharModal()">Cancelar</button>
          <button type="button" class="btn-salvar" onclick="salvarEdicao()">Salvar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal de Confirmação de Exclusão -->
  <div id="modal-confirmacao" class="modal">
    <div class="modal-content" style="max-width: 400px;">
      <span class="close-btn" onclick="fecharModalConfirmacao()">&times;</span>
      <h2 style="color: #ff4444; margin-bottom: 1.5rem;">Confirmar Exclusão</h2>
      <p id="confirmacao-mensagem">Tem certeza que deseja deletar este usuário/cliente?</p>
      <p id="confirmacao-detalhes" style="font-size: 0.9em; color: #aaa; margin-bottom: 20px;"></p>
      <div class="form-buttons">
        <button type="button" class="btn-cancelar" onclick="fecharModalConfirmacao()">Cancelar</button>
        <button type="button" class="btn-salvar" onclick="confirmarExclusao()">Sim, Deletar</button>
      </div>
    </div>
  </div>
</body>
</html>