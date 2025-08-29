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
 * DETECÇÃO DA TABELA DE CLIENTE
 */
function detectaTabelaCliente(PDO $pdo): ?array {
    $candidatas = ['cliente', 'clientes', 'usuario', 'usuarios'];
    foreach ($candidatas as $tbl) {
        $st = $pdo->prepare("SHOW TABLES LIKE :t");
        $t = $tbl;
        $st->bindParam(':t', $t);
        $st->execute();
        if (!$st->fetchColumn()) continue;

        $cols = [];
        $q = $pdo->query("SHOW COLUMNS FROM `$tbl`");
        foreach ($q as $r) $cols[] = $r['Field'];
        
        $necessarias = ['nome', 'telefone', 'email', 'cpf', 'endereco'];
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

$sql = "SELECT os.*";

if ($temCliente) {
    $sql .= ", c.nome AS nome_cliente, c.telefone AS telefone_cliente, c.email AS email_cliente, c.cpf AS cpf_cliente, c.endereco AS endereco_cliente";
    $sql .= " FROM ordem_serv os";
    $sql .= " LEFT JOIN `{$tblCliente['nome']}` c ON c.cpf = os.cpf";
} else {
    $sql .= ", os.nome AS nome_cliente, os.telefone AS telefone_cliente, os.email AS email_cliente, os.cpf AS cpf_cliente, os.endereco AS endereco_cliente";
    $sql .= " FROM ordem_serv os";
}

$sql .= " WHERE 1=1";
$params = [];

if ($nome !== '') {
    $sql .= $temCliente ? " AND c.nome LIKE :nome" : " AND os.nome LIKE :nome";
    $params[':nome'] = "%".$nome."%";
}
if ($cpf_cnpj !== '') {
    $sql .= $temCliente ? " AND c.cpf LIKE :cpf" : " AND os.cpf LIKE :cpf";
    $params[':cpf'] = "%".$cpf_cnpj."%";
}
if ($telefone !== '') {
    $sql .= $temCliente ? " AND c.telefone LIKE :tel" : " AND os.telefone LIKE :tel";
    $params[':tel'] = "%".$telefone."%";
}
if ($email !== '') {
    $sql .= $temCliente ? " AND c.email LIKE :email" : " AND os.email LIKE :email";
    $params[':email'] = "%".$email."%";
}

$sql .= " ORDER BY os.data_entrada DESC, os.id_ordem_serv DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $ordens = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na consulta SQL: " . $e->getMessage() . "<br>SQL: " . $sql);
}
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
</head>
<body>
  <!-- Sidebar -->
  <nav class="sidebar">
    <div class="logo">
      <img src="img/logo.png" alt="Logo do sistema">
    </div>
    <ul class="menu">
      <?php foreach ($menuItems as $item): ?>
        <li><a href="<?php echo $item['href']; ?>" <?php echo $item['href'] === 'ordem_serv.php' ? 'class="active"' : ''; ?>><?php echo $item['icon']; ?> <span><?php echo $item['text']; ?></span></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <div class="form-container">
    <h2>📦 Lista de Ordens de Serviço</h2>

    <?php if (!$temCliente): ?>
      <div class="alert-info">
        Pesquisa por <b>Nome/Telefone/E-mail/CPF/Endereço</b> desativada (sem tabela de cliente detectada).  
        Filtro por <b>CPF/CNPJ</b> funcionando normalmente (usando campo de ordem_serv).
      </div>
    <?php else: ?>
      <div class="alert-info">
        <b>Informação:</b> Dados do cliente sendo buscados da tabela <b><?php echo $tblCliente['nome']; ?></b>.
      </div>
    <?php endif; ?>

    <!-- Filtro de Busca -->
    <form id="filtro-form" method="get" style="margin-bottom: 2rem;">
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;">
        <input type="text" name="nome" value="<?php echo htmlspecialchars($nome); ?>" placeholder="Nome">
        <input type="text" name="cpf_cnpj" id="cpf_cnpj" value="<?php echo htmlspecialchars($cpf_cnpj); ?>" placeholder="CPF/CNPJ">
        <input type="text" name="telefone" id="telefone" value="<?php echo htmlspecialchars($telefone); ?>" placeholder="Telefone" maxlength="15">
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="E-mail">
      </div>
      <div style="margin-top:1rem;display:flex;gap:1rem;flex-wrap:wrap;">
        <button type="submit">Buscar</button>
        <a href="ordem_serv.php"><button type="button">Limpar</button></a>
      </div>
    </form>

    <!-- Tabela -->
      <div style="overflow-x:auto;">
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
            <tr data-id="<?php echo (int)$o['id_ordem_serv']; ?>">
              <td><?php echo htmlspecialchars($o['nome_cliente'] ?? '-'); ?></td>
              <td><?php echo htmlspecialchars($o['cpf_cliente'] ?? '-'); ?></td>
              <td><?php echo htmlspecialchars($o['telefone_cliente'] ?? '-'); ?></td>
              <td><?php echo htmlspecialchars($o['email_cliente'] ?? '-'); ?></td>
              <td><?php echo htmlspecialchars($o['endereco_cliente'] ?? '-'); ?></td>
              <td><?php echo htmlspecialchars($o['Aparelho'] ?? '-'); ?></td>
              <td><?php echo htmlspecialchars($o['servico'] ?? '-'); ?></td>
              <td><?php echo !empty($o['data_entrada']) ? date('d/m/Y', strtotime($o['data_entrada'])) : '-'; ?></td>
              <td><?php echo !empty($o['data_saida']) ? date('d/m/Y', strtotime($o['data_saida'])) : '-'; ?></td>
              <td><?php echo !empty($o['valor']) ? 'R$ '.number_format((float)$o['valor'], 2, ',', '.') : '-'; ?></td>
              <td><?php echo htmlspecialchars($o['tipo_pagamento'] ?? '-'); ?></td>
              <td class="status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($statusTxt); ?></td>
              <td class="actions">
                <!-- Botões -->
                <button onclick="window.location.href='editar_ordem.php?id=<?php echo (int)$o['id_ordem_serv']; ?>'">✏️</button>
                <button onclick="gerarPDF(<?php echo (int)$o['id_ordem_serv']; ?>)">📄</button>
                <button onclick="if(confirm('Tem certeza que deseja excluir esta ordem?')) { window.location.href='deletar_ordem.php?id=<?php echo (int)$o['id_ordem_serv']; ?>'; }">🗑️</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="13" style="text-align:center;">Nenhuma ordem encontrada.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- jsPDF para gerar PDF via JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
const { jsPDF } = window.jspdf;

function gerarPDF(id_ordem) {
  const linha = document.querySelector(`tr[data-id='${id_ordem}']`);
  if (!linha) { alert('Ordem não encontrada!'); return; }

  const cells = linha.querySelectorAll('td');
  const dados = {
    "Cliente": cells[0].innerText,
    "CPF": cells[1].innerText,
    "Telefone": cells[2].innerText,
    "Email": cells[3].innerText,
    "Endereço": cells[4].innerText,
    "Aparelho": cells[5].innerText,
    "Serviço": cells[6].innerText,
    "Data Entrada": cells[7].innerText,
    "Data Saída": cells[8].innerText,
    "Valor": cells[9].innerText,
    "Pagamento": cells[10].innerText,
    "Status": cells[11].innerText,
  };

  const doc = new jsPDF();
  const pageWidth = doc.internal.pageSize.getWidth();

  // Título
  doc.setFontSize(18);
  doc.setFont("helvetica", "bold");
  doc.text("Ordem de Serviço", pageWidth / 2, 20, { align: "center" });

  // Linha horizontal abaixo do título
  doc.setLineWidth(0.5);
  doc.line(20, 25, pageWidth - 20, 25);

  // Conteúdo
  doc.setFontSize(12);
  doc.setFont("helvetica", "normal");
  let y = 35;

  Object.entries(dados).forEach(([key, value]) => {
    // Nome do campo em negrito
    doc.setFont("helvetica", "bold");
    doc.text(`${key}: `, 20, y);
    // Valor normal
    doc.setFont("helvetica", "normal");
    doc.text(`${value}`, 55, y);
    y += 8;
  });

  // Linha final
  doc.setLineWidth(0.3);
  doc.line(20, y, pageWidth - 20, y);

  doc.save(`Ordem_${id_ordem}.pdf`);
}
</script>

</body>
</html>
