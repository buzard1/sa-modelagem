<?php 
session_start();
require_once 'conexao.php';

/**
 * PERMISSÃO
 */
if (!isset($_SESSION['cargo']) || !in_array($_SESSION['cargo'], ["Gerente","Atendente","Tecnico"])) {
    header("Location: dashboard.php");
    exit();
}

/**
 * MENU
 */
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
$menuItems = isset($_SESSION['cargo']) && isset($menus[$_SESSION['cargo']]) ? $menus[$_SESSION['cargo']] : [];

/**
 * DETECÇÃO OPCIONAL DE TABELA DE CLIENTE
 * - Verifica se existe `usuario` ou `usuarios` com as colunas necessárias.
 */
function detectaTabelaCliente(PDO $pdo): ?array {
    $candidatas = ['usuario', 'usuarios', 'cliente', 'clientes'];
    foreach ($candidatas as $tbl) {
        $st = $pdo->prepare("SHOW TABLES LIKE :t");
        $t = $tbl;
        $st->bindParam(':t', $t);
        $st->execute();
        if (!$st->fetchColumn()) continue;

        $cols = [];
        $q = $pdo->query("SHOW COLUMNS FROM `$tbl`");
        foreach ($q as $r) $cols[] = $r['Field'];
        $necessarias = ['id_usuario', 'nome', 'telefone', 'email', 'cpf', 'endereco'];
        $ok = !array_diff($necessarias, $cols);
        if ($ok) return ['nome' => $tbl, 'colunas' => $cols];
    }
    return null;
}

$tblCliente = detectaTabelaCliente($pdo);
$temCliente = (bool)$tblCliente;

/**
 * FILTROS
 */
$nome      = trim($_GET['nome'] ?? '');
$cpf_cnpj  = trim($_GET['cpf_cnpj'] ?? '');
$telefone  = trim($_GET['telefone'] ?? '');
$email     = trim($_GET['email'] ?? '');

// Construir a query SQL
$sql = "SELECT os.*";
if ($temCliente) {
    $sql .= ", c.nome AS nome_cliente, c.telefone AS telefone_cliente, c.email AS email_cliente, c.cpf AS cpf_cliente, c.endereco AS endereco_cliente";
} else {
    $sql .= ", os.nome_cliente, os.telefone AS telefone_cliente, os.email AS email_cliente, os.cpf AS cpf_cliente, os.endereco AS endereco_cliente";
}

$sql .= " FROM ordem_serv os";

if ($temCliente) {
    $sql .= " LEFT JOIN `{$tblCliente['nome']}` c ON c.id_usuario = os.id_usuario";
}

$sql .= " WHERE 1=1";

$params = [];

// Aplicar filtros
if ($nome !== '') {
    if ($temCliente) {
        $sql .= " AND c.nome LIKE :nome";
    } else {
        $sql .= " AND os.nome_cliente LIKE :nome";
    }
    $params[':nome'] = "%".$nome."%";
}

if ($cpf_cnpj !== '') {
    if ($temCliente) {
        $sql .= " AND c.cpf LIKE :cpf";
    } else {
        $sql .= " AND os.cpf LIKE :cpf";
    }
    $params[':cpf'] = "%".$cpf_cnpj."%";
}

if ($telefone !== '') {
    if ($temCliente) {
        $sql .= " AND c.telefone LIKE :tel";
    } else {
        $sql .= " AND os.telefone LIKE :tel";
    }
    $params[':tel'] = "%".$telefone."%";
}

if ($email !== '') {
    if ($temCliente) {
        $sql .= " AND c.email LIKE :email";
    } else {
        $sql .= " AND os.email LIKE :email";
    }
    $params[':email'] = "%".$email."%";
}

$sql .= " ORDER BY os.data_entrada DESC, os.id_ordem_serv DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ordens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ordens de Serviço</title>
  <link rel="stylesheet" href="css/sidebar.css"/>
  <link rel="stylesheet" href="css/form.css" />
  <link rel="stylesheet" href="css/pedidos.css" />
  <link rel="icon" href="img/logo.png" type="image/png">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <style>
    .pedidos-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
      background-color: #2c2c2c;
      border-radius: 8px;
      overflow: hidden;
    }
    .pedidos-table th, .pedidos-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #444;
    }
    .pedidos-table th {
      background-color: #03dac6;
      color: #000;
      font-weight: bold;
    }
    .pedidos-table tr:hover {
      background-color: #3c3c3c;
    }
    .endereco-column {
      max-width: 200px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .status {
      padding: 4px 8px;
      border-radius: 4px;
      font-weight: bold;
      text-align: center;
      display: inline-block;
    }
    .status.pendente {
      background-color: #ff9800;
      color: #000;
    }
    .status.andamento {
      background-color: #2196f3;
      color: #fff;
    }
    .status.concluido {
      background-color: #4caf50;
      color: #fff;
    }
    .status.cancelado {
      background-color: #f44336;
      color: #fff;
    }
    .actions {
      display: flex;
      gap: 8px;
    }
    .action-btn {
      background: none;
      border: none;
      cursor: pointer;
      font-size: 16px;
      position: relative;
    }
    .tooltip-text {
      visibility: hidden;
      width: 60px;
      background-color: #555;
      color: #fff;
      text-align: center;
      border-radius: 6px;
      padding: 5px;
      position: absolute;
      z-index: 1;
      bottom: 125%;
      left: 50%;
      margin-left: -30px;
      opacity: 0;
      transition: opacity 0.3s;
    }
    .action-btn:hover .tooltip-text {
      visibility: visible;
      opacity: 1;
    }
    .alert-info {
      background-color: #03a9f4;
      color: #fff;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <nav class="sidebar">
    <div class="logo"><img src="img/logo.png" alt="Logo do sistema"></div>
    <ul class="menu">
      <?php foreach ($menuItems as $item): ?>
        <li><a href="<?php echo $item['href']; ?>"><?php echo $item['icon']; ?> <span><?php echo $item['text']; ?></span></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <div class="form-container">
    <h2>📦 Lista de Ordens de Serviço</h2>

    <?php if (!$temCliente): ?>
      <div class="alert-info">
        Pesquisa por <b>Nome/Telefone/E-mail/CPF/Endereço</b> desativada (sem tabela de cliente detectada).
        Filtro por <b>CPF/CNPJ</b> funcionando normalmente (usando campo de ordem_serv).
        Se desejar habilitar, crie a tabela <code>usuario</code> ou <code>usuarios</code> com colunas:
        <code>id_usuario, nome, telefone, email, cpf, endereco</code> e relacione por <code>ordem_serv.id_usuario</code>.
      </div>
    <?php endif; ?>

    <!-- Filtro de Busca -->
    <form id="filtro-form" method="get" style="margin-bottom: 2rem;">
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;">
        <input type="text" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Nome" style="padding:0.5rem;border-radius:8px;border:none;">
        <input type="text" name="cpf_cnpj" id="cpf_cnpj" value="<?php echo htmlspecialchars($cpf_cnpj); ?>" placeholder="CPF/CNPJ" style="padding:0.5rem;border-radius:8px;border:none;">
        <input type="text" name="telefone" id="telefone" value="<?php echo htmlspecialchars($telefone); ?>" placeholder="Telefone" style="padding:0.5rem;border-radius:8px;border:none;" maxlength="15">
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="E-mail" style="padding:0.5rem;border-radius:8px;border:none;">
      </div>
      <div style="margin-top:1rem;display:flex;gap:1rem;flex-wrap:wrap;">
        <button type="submit" style="padding:0.6rem 1.2rem;background-color:#03dac6;border:none;border-radius:8px;color:#000;cursor:pointer;">Buscar</button>
        <a href="ordem_serv.php" style="text-decoration:none;">
          <button type="button" style="padding:0.6rem 1.2rem;background-color:#444;border:none;border-radius:8px;color:#fff;cursor:pointer;">Limpar</button>
        </a>
      </div>
    </form>

    <!-- Tabela -->
    <table class="pedidos-table">
      <thead>
        <tr>
          <th>Cliente</th>
          <th>CPF</th>
          <th>Telefone</th>
          <th>Email</th>
          <th>Endereço</th>
          <th>Aparelho</th>
          <th>Serviço</th>
          <th>Data Entrada</th>
          <th>Data Saída</th>
          <th>Valor</th>
          <th>Pagamento</th>
          <th>Status</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($ordens)): ?>
          <?php foreach ($ordens as $o): ?>
            <?php
              $statusTxt = $o['status'] ?? '';
              $statusClass = 'pendente';
              if (mb_strtolower($statusTxt,'UTF-8') === 'em andamento' || mb_strtolower($statusTxt,'UTF-8') === 'andamento') $statusClass = 'andamento';
              if (mb_strtolower($statusTxt,'UTF-8') === 'concluído' || mb_strtolower($statusTxt,'UTF-8') === 'concluido') $statusClass = 'concluido';
              if (mb_strtolower($statusTxt,'UTF-8') === 'cancelado') $statusClass = 'cancelado';
            ?>
            <tr 
              data-id="<?php echo (int)$o['id_ordem_serv']; ?>" 
              data-pecas="[]"
            >
              <td><?php echo htmlspecialchars($o['nome_cliente'] ?? '-'); ?></td>
              <td><?php echo htmlspecialchars($o['cpf_cliente'] ?? '-'); ?></td>
              <td><?php echo htmlspecialchars($o['telefone_cliente'] ?? '-'); ?></td>
              <td><?php echo htmlspecialchars($o['email_cliente'] ?? '-'); ?></td>
              <td class="endereco-column"><?php echo htmlspecialchars($o['endereco_cliente'] ?? '-'); ?></td>
              <td><?php echo htmlspecialchars($o['Aparelho'] ?? '-'); ?></td>
              <td><?php echo htmlspecialchars($o['servico'] ?? '-'); ?></td>
              <td><?php echo !empty($o['data_entrada']) ? date('d/m/Y', strtotime($o['data_entrada'])) : '-'; ?></td>
              <td><?php echo !empty($o['data_saida']) ? date('d/m/Y', strtotime($o['data_saida'])) : '-'; ?></td>
              <td><?php echo !empty($o['valor']) ? 'R$ '.number_format((float)$o['valor'], 2, ',', '.') : '-'; ?></td>
              <td><?php echo htmlspecialchars($o['tipo_pagamento'] ?? '-'); ?></td>
              <td class="status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($statusTxt); ?></td>
              <td class="actions">
                <button class="action-btn edit-btn" onclick="abrirModalEdicao(this)">✏️<span class="tooltip-text">Editar</span></button>
                <button class="action-btn pdf-btn" onclick="gerarPDF(this)">📄<span class="tooltip-text">Gerar PDF</span></button>
                <button class="action-btn delete-btn" onclick="deletarLinha(this)">🗑️<span class="tooltip-text">Deletar</span></button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="13" style="text-align:center;">Nenhuma ordem encontrada.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <script>
    // Funções JavaScript para manipulação da tabela
    function abrirModalEdicao(btn) {
      const row = btn.closest('tr');
      const id = row.getAttribute('data-id');
      alert('Editar ordem ID: ' + id);
      // Implementar a lógica de edição aqui
    }

    function gerarPDF(btn) {
      const row = btn.closest('tr');
      const id = row.getAttribute('data-id');
      alert('Gerar PDF para ordem ID: ' + id);
      // Implementar a geração de PDF aqui
    }

    function deletarLinha(btn) {
      if (confirm('Tem certeza que deseja excluir esta ordem de serviço?')) {
        const row = btn.closest('tr');
        const id = row.getAttribute('data-id');
        
        // Simular uma requisição AJAX para excluir
        fetch('excluir_ordem.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            row.remove();
            alert('Ordem excluída com sucesso!');
          } else {
            alert('Erro ao excluir ordem: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Erro:', error);
          alert('Erro ao excluir ordem.');
        });
      }
    }

    // Formatação de CPF/CNPJ e telefone nos campos de filtro
    document.getElementById('cpf_cnpj').addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d)/, '$1.$2')
                     .replace(/(\d{3})(\d)/, '$1.$2')
                     .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
      } else {
        value = value.replace(/^(\d{2})(\d)/, '$1.$2')
                     .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
                     .replace(/\.(\d{3})(\d)/, '.$1/$2')
                     .replace(/(\d{4})(\d)/, '$1-$2');
      }
      e.target.value = value;
    });

    document.getElementById('telefone').addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length > 10) {
        value = value.replace(/^(\d{2})(\d)/, '($1) $2')
                     .replace(/(\d{5})(\d)/, '$1-$2');
      } else {
        value = value.replace(/^(\d{2})(\d)/, '($1) $2')
                     .replace(/(\d{4})(\d)/, '$1-$2');
      }
      e.target.value = value;
    });
  </script>
</body>
</html>