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
 * - Se existir `usuario` OU `usuarios` com id_usuario,nome,telefone,email, faz JOIN.
 */
function detectaTabelaCliente(PDO $pdo): ?array {
    $candidatas = ['usuario','usuarios'];
    foreach ($candidatas as $tbl) {
        // existe tabela?
        $st = $pdo->prepare("SHOW TABLES LIKE :t");
        $t = $tbl;
        $st->bindParam(':t', $t);
        $st->execute();
        if (!$st->fetchColumn()) continue;

        // tem colunas necessárias?
        $cols = [];
        $q = $pdo->query("SHOW COLUMNS FROM `$tbl`");
        foreach ($q as $r) $cols[] = $r['Field'];
        $necessarias = ['id_usuario','nome','telefone','email'];
        $ok = !array_diff($necessarias, $cols);
        if ($ok) return ['nome'=>$tbl];
    }
    return null;
}

$tblCliente = detectaTabelaCliente($pdo); // null se não houver
$temCliente = (bool)$tblCliente;

/**
 * FILTROS
 */
$nome      = trim($_GET['nome'] ?? '');
$cpf_cnpj  = trim($_GET['cpf_cnpj'] ?? '');
$telefone  = trim($_GET['telefone'] ?? '');
$email     = trim($_GET['email'] ?? '');

$sql = "SELECT os.*";
if ($temCliente) $sql .= ", c.nome AS nome_cliente, c.telefone AS telefone_cliente, c.email AS email_cliente";
else             $sql .= ", '' AS nome_cliente, '' AS telefone_cliente, '' AS email_cliente";

$sql .= " FROM ordem_serv os";
if ($temCliente) $sql .= " LEFT JOIN `{$tblCliente['nome']}` c ON c.id_usuario = os.id_usuario";
$sql .= " WHERE 1=1";

$params = [];

// CPF/CNPJ SEMPRE FUNCIONA (está na ordem_serv)
if ($cpf_cnpj !== '') { $sql .= " AND os.cpf LIKE :cpf"; $params[':cpf'] = "%".$cpf_cnpj."%"; }

// Os de baixo só aplicam se existir tabela de cliente
if ($temCliente && $nome   !== '') { $sql .= " AND c.nome LIKE :nome";         $params[':nome'] = "%".$nome."%"; }
if ($temCliente && $telefone !== '') { $sql .= " AND c.telefone LIKE :tel";     $params[':tel']  = "%".$telefone."%"; }
if ($temCliente && $email   !== '') { $sql .= " AND c.email LIKE :email";       $params[':email']= "%".$email."%"; }

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
    /* Mini-aba de peças (igual ao seu) */
    .mini-aba{display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#1e1e1e;padding:20px;border-radius:8px;z-index:1000;width:400px;box-shadow:0 0 20px rgba(0,0,0,0.5)}
    .mini-aba h3{margin-top:0;color:#03dac6}
    .lista-pecas{max-height:200px;overflow-y:auto;margin:10px 0}
    .peca-item{padding:8px;margin:5px 0;background:#333;border-radius:4px;cursor:pointer}
    .peca-item:hover{background:#444}
    .peca-adicionada{display:flex;justify-content:space-between;align-items:center;padding:8px;margin:5px 0;background:#333;border-radius:4px}
    .peca-info{flex-grow:1}
    .peca-quantidade{width:50px;margin:0 10px;padding:3px;text-align:center;border-radius:4px;border:none;background:#444;color:white}
    .remove-peca{background:#ff4444;color:white;border:none;border-radius:4px;padding:2px 5px;cursor:pointer}
    .overlay{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:999}
    .modal{display:none;position:fixed;z-index:1000;left:0;top:0;width:100%;height:100%;background-color:rgba(0,0,0,0.7)}
    .modal-content{background-color:#1e1e1e;margin:5% auto;padding:20px;border-radius:8px;width:80%;max-width:600px;box-shadow:0 0 20px rgba(0,0,0,0.5)}
    .close-btn{color:#aaa;float:right;font-size:28px;font-weight:bold;cursor:pointer}
    .close-btn:hover{color:#fff}
    .form-group{margin-bottom:15px}
    .form-input{width:100%;padding:8px;border-radius:4px;border:none;background:#333;color:white}
    .form-buttons{display:flex;justify-content:flex-end;gap:10px;margin-top:20px}
    .btn-salvar{padding:8px 16px;background-color:#03dac6;border:none;border-radius:4px;color:#000;cursor:pointer}
    .btn-cancelar{padding:8px 16px;background-color:#444;border:none;border-radius:4px;color:#fff;cursor:pointer}
    .alert-info{background:#123;padding:10px;border-radius:8px;margin-bottom:15px;color:#aee}
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
    <h2>📦 Lista de Ordem de serviço</h2>

    <?php if (!$temCliente): ?>
      <div class="alert-info">
        Pesquisa por <b>Nome/Telefone/E-mail</b> desativada (sem tabela de clientes detectada).
        Filtro por <b>CPF/CNPJ</b> funcionando normalmente.
        Se desejar habilitar, crie a tabela <code>usuario</code> ou <code>usuarios</code> com colunas:
        <code>id_usuario, nome, telefone, email</code> e relacione por <code>ordem_serv.id_usuario</code>.
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
              <td><?php echo htmlspecialchars($o['nome_cliente']); ?></td>
              <td><?php echo htmlspecialchars($o['cpf']); ?></td>
              <td><?php echo htmlspecialchars($o['telefone_cliente']); ?></td>
              <td><?php echo htmlspecialchars($o['email_cliente']); ?></td>
              <td><?php echo htmlspecialchars($o['Aparelho']); ?></td>
              <td><?php echo htmlspecialchars($o['servico']); ?></td>
              <td><?php echo $o['data_entrada'] ? date('d/m/Y', strtotime($o['data_entrada'])) : '-'; ?></td>
              <td><?php echo $o['data_saida'] ? date('d/m/Y', strtotime($o['data_saida'])) : '-'; ?></td>
              <td><?php echo 'R$ '.number_format((float)$o['valor'],2,',','.'); ?></td>
              <td><?php echo htmlspecialchars($o['tipo_pagamento']); ?></td>
              <td class="status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($statusTxt); ?></td>
              <td class="actions">
                <button class="action-btn edit-btn" onclick="abrirModalEdicao(this)">✏️<span class="tooltip-text">Editar</span></button>
                <button class="action-btn pdf-btn" onclick="gerarPDF(this)">📄<span class="tooltip-text">Gerar PDF</span></button>
                <button class="action-btn delete-btn" onclick="deletarLinha(this)">🗑️<span class="tooltip-text">Deletar</span></button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="12" style="text-align:center;">Nenhuma ordem encontrada.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

<!-- Modal de Edição -->
<div id="modal-edicao" class="modal">
  <div class="modal-content">
    <span class="close-btn" onclick="fecharModal()">&times;</span>
    <h2 style="color: #03dac6; margin-bottom: 1.5rem;">Editar Ordem de Serviço</h2>
    <form id="form-edicao">
      <input type="hidden" id="edit-id">
      <div class="form-group">
        <label for="edit-cliente">Cliente:</label>
        <input type="text" id="edit-cliente" class="form-input" disabled>
      </div>
      <div class="form-group">
        <label for="edit-aparelho">Aparelho:</label>
        <input type="text" id="edit-aparelho" class="form-input">
      </div>
      <div class="form-group">
        <label for="edit-servico">Serviço:</label>
        <input type="text" id="edit-servico" class="form-input">
      </div>

      <!-- Peças -->
      <div class="form-group">
        <label>Peças utilizadas:</label>
        <div id="lista-pecas-adicionadas" style="margin-bottom:10px;"></div>
        <div style="display:flex;gap:10px;">
          <button type="button" onclick="abrirMiniAbaPecas()" style="background:#03dac6;color:black;border:none;border-radius:4px;padding:5px 10px;cursor:pointer;">Adicionar Peça</button>
        </div>
      </div>

      <div class="form-group">
        <label for="edit-data-entrada">Data Entrada:</label>
        <input type="date" id="edit-data-entrada" class="form-input">
      </div>
      <div class="form-group">
        <label for="edit-data-saida">Data Saída:</label>
        <input type="date" id="edit-data-saida" class="form-input">
      </div>
      <div class="form-group">
        <label for="edit-valor">Valor:</label>
        <input type="text" id="edit-valor" class="form-input">
      </div>
      <div class="form-group">
        <label for="edit-pagamento">Pagamento:</label>
        <select id="edit-pagamento" class="form-input">
          <option value="Cartão">Cartão</option>
          <option value="Pix">Pix</option>
          <option value="Dinheiro">Dinheiro</option>
          <option value="Boleto">Boleto</option>
        </select>
      </div>
      <div class="form-group">
        <label for="edit-status">Status:</label>
        <select id="edit-status" class="form-input">
          <option value="Pendente">Pendente</option>
          <option value="Em andamento">Em andamento</option>
          <option value="Concluído">Concluído</option>
          <option value="Cancelado">Cancelado</option>
        </select>
      </div>
      <div class="form-buttons">
        <button type="button" class="btn-salvar" onclick="salvarEdicao()">Salvar</button> 
        <button type="button" class="btn-cancelar" onclick="fecharModal()">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<!-- Mini Aba de Peças -->
<div id="overlay" class="overlay" onclick="fecharMiniAbaPecas()"></div>
<div id="mini-aba-pecas" class="mini-aba">
  <h3>Adicionar Peça</h3>
  <input type="text" id="busca-peca" placeholder="Buscar peça..." style="width:100%;padding:8px;margin-bottom:10px;border-radius:4px;border:none;">
  <div class="lista-pecas">
    <div class="peca-item" onclick="selecionarPeca('Tela LCD iPhone 11', 'TELA-IP11')">Tela LCD iPhone 11</div>
    <div class="peca-item" onclick="selecionarPeca('Bateria Motorola G9', 'BAT-MOTG9')">Bateria Motorola G9</div>
    <div class="peca-item" onclick="selecionarPeca('Botão Power Samsung A21s', 'BTN-SAMA21')">Botão Power Samsung A21s</div>
    <div class="peca-item" onclick="selecionarPeca('Conector de Carga Universal', 'CON-USB')">Conector de Carga Universal</div>
    <div class="peca-item" onclick="selecionarPeca('Película Protetora', 'PEL-UNI')">Película Protetora</div>
  </div>
  <div style="margin-top:15px;display:flex;align-items:center;justify-content:space-between;">
    <div style="display:flex;align-items:center;">
      <label style="margin-right:10px;">Quantidade:</label>
      <input type="number" id="peca-quantidade" min="1" value="1" style="width:60px;padding:5px;border-radius:4px;border:none;background:#444;color:white;">
    </div>
    <button type="button" onclick="adicionarPeca()" style="background:#03dac6;color:black;border:none;border-radius:4px;padding:5px 10px;cursor:pointer;">Adicionar</button>
  </div>
  <button type="button" onclick="fecharMiniAbaPecas()" style="margin-top:10px;background:#444;color:white;border:none;border-radius:4px;padding:5px 10px;cursor:pointer;">Cancelar</button>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="modal-confirmacao" class="modal">
  <div class="modal-content" style="max-width:400px;">
    <span class="close-btn" onclick="fecharModalConfirmacao()">&times;</span>
    <h2 style="color:#ff4444;margin-bottom:1.5rem;">Confirmar Exclusão</h2>
    <p id="confirmacao-mensagem">Tem certeza que deseja deletar esta ordem de serviço?</p>
    <p id="confirmacao-detalhes" style="font-size:.9em;color:#aaa;margin-bottom:20px;"></p>
    <div class="form-buttons">
      <button type="button" class="btn-cancelar" onclick="fecharModalConfirmacao()">Cancelar</button>
      <button type="button" class="btn-salvar" style="background-color:#ff4444;" onclick="confirmarExclusao()">Sim, Deletar</button>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Máscara CPF/CNPJ
const inputCpf = document.getElementById('cpf_cnpj');
if (inputCpf){
  inputCpf.addEventListener('input', function () {
    let value = this.value.replace(/\D/g, '').slice(0,14);
    if (value.length <= 11) {
      value = value.replace(/(\d{3})(\d)/, '$1.$2');
      value = value.replace(/(\d{3})(\d)/, '$1.$2');
      value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    } else {
      value = value.replace(/^(\d{2})(\d)/, '$1.$2');
      value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
      value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
      value = value.replace(/(\d{4})(\d)/, '$1-$2');
    }
    this.value = value;
  });
}

// Máscara telefone
const inputTel = document.getElementById('telefone');
if (inputTel){
  inputTel.addEventListener('input', function() {
    let v = this.value.replace(/\D/g,'').slice(0,11);
    if (v.length <= 10) {
      v = v.replace(/(\d{2})(\d)/, '($1) $2').replace(/(\d{4})(\d)/, '$1-$2');
    } else {
      v = v.replace(/(\d{2})(\d)/, '($1) $2').replace(/(\d{5})(\d)/, '$1-$2');
    }
    this.value = v;
  });
}

/* --------- Peças UI --------- */
let linhaEditando = null;
let pecaSelecionada = null;
function abrirMiniAbaPecas(){ document.getElementById('overlay').style.display='block'; document.getElementById('mini-aba-pecas').style.display='block'; document.getElementById('peca-quantidade').value=1; pecaSelecionada=null; }
function fecharMiniAbaPecas(){ document.getElementById('overlay').style.display='none'; document.getElementById('mini-aba-pecas').style.display='none'; }
function selecionarPeca(nome,codigo){
  pecaSelecionada = {nome, codigo};
  const pecas = document.querySelectorAll('.peca-item');
  pecas.forEach(p=>{p.style.background='#333'; p.style.color='#fff';});
  event.target.style.background='#03dac6'; event.target.style.color='#000';
}
function adicionarPeca(){
  if (!pecaSelecionada) return;
  const q = parseInt(document.getElementById('peca-quantidade').value)||1;
  const div = document.createElement('div');
  div.className='peca-adicionada';
  div.dataset.codigo = pecaSelecionada.codigo;
  div.innerHTML = `<div class="peca-info">${pecaSelecionada.nome} (${pecaSelecionada.codigo})</div>
                   <input type="number" class="peca-quantidade" value="${q}" min="1">
                   <button class="remove-peca" onclick="this.closest('.peca-adicionada').remove()">X</button>`;
  document.getElementById('lista-pecas-adicionadas').appendChild(div);
  fecharMiniAbaPecas();
}

/* --------- Editar --------- */
function abrirModalEdicao(botao){
  linhaEditando = botao.closest('tr');
  const tds = linhaEditando.cells;
  document.getElementById('edit-id').value = linhaEditando.dataset.id;
  document.getElementById('edit-cliente').value  = tds[0].textContent.trim();
  document.getElementById('edit-aparelho').value = tds[4].textContent.trim();
  document.getElementById('edit-servico').value  = tds[5].textContent.trim();

  const de = tds[6].textContent.trim(); // dd/mm/yyyy
  const ds = tds[7].textContent.trim();
  document.getElementById('edit-data-entrada').value = de ? dataBRtoISO(de) : '';
  document.getElementById('edit-data-saida').value   = (ds && ds !== '-') ? dataBRtoISO(ds) : '';

  document.getElementById('edit-valor').value = tds[8].textContent.replace('R$ ','').replace(/\./g,'').replace(',','.');
  document.getElementById('edit-pagamento').value = tds[9].textContent.trim();
  document.getElementById('edit-status').value    = tds[10].textContent.trim();

  // limpa peças da UI
  document.getElementById('lista-pecas-adicionadas').innerHTML = '';
  document.getElementById('modal-edicao').style.display='block';
}
function fecharModal(){ document.getElementById('modal-edicao').style.display='none'; }
function dataBRtoISO(d){ const [dd,mm,yy] = d.split('/'); return `${yy}-${mm}-${dd}`; }
function dataISOtoBR(d){ if(!d) return '-'; const x = new Date(d); return x.toLocaleDateString('pt-BR'); }

function salvarEdicao(){
  const id   = document.getElementById('edit-id').value;
  const body = new URLSearchParams({
    id,
    aparelho: document.getElementById('edit-aparelho').value,
    servico:  document.getElementById('edit-servico').value,
    data_entrada: document.getElementById('edit-data-entrada').value,
    data_saida:   document.getElementById('edit-data-saida').value,
    valor:    document.getElementById('edit-valor').value,
    tipo_pagamento: document.getElementById('edit-pagamento').value,
    status:   document.getElementById('edit-status').value
  });

  fetch('atualizar_ordem.php', {
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body: body.toString()
  }).then(r=>r.json()).then(res=>{
    if (res.ok){
      // Atualiza linha na tabela
      const t = linhaEditando.cells;
      t[4].textContent = res.data.Aparelho;
      t[5].textContent = res.data.servico;
      t[6].textContent = res.data.data_entrada_br;
      t[7].textContent = res.data.data_saida_br;
      t[8].textContent = 'R$ '+res.data.valor_fmt;
      t[9].textContent = res.data.tipo_pagamento;
      t[10].textContent = res.data.status;
      t[10].className = 'status ' + res.data.status_class;
      fecharModal();
    } else {
      alert('Erro ao salvar: ' + res.msg);
    }
  }).catch(e=>alert('Falha na requisição: '+e));
}

/* --------- Excluir --------- */
let linhaParaDeletar = null;
function deletarLinha(botao){
  linhaParaDeletar = botao.closest('tr');
  const cliente = linhaParaDeletar.cells[0].textContent;
  const aparelho= linhaParaDeletar.cells[4].textContent;
  const servico = linhaParaDeletar.cells[5].textContent;
  document.getElementById('confirmacao-mensagem').textContent = 'Tem certeza que deseja deletar esta ordem de serviço?';
  document.getElementById('confirmacao-detalhes').innerHTML = `<strong>Cliente:</strong> ${cliente}<br><strong>Aparelho:</strong> ${aparelho}<br><strong>Serviço:</strong> ${servico}`;
  document.getElementById('modal-confirmacao').style.display='block';
}
function fecharModalConfirmacao(){ document.getElementById('modal-confirmacao').style.display='none'; linhaParaDeletar=null; }
function confirmarExclusao(){
  if (!linhaParaDeletar) return;
  const id = linhaParaDeletar.dataset.id;
  const confirmacaoFinal = prompt('Digite "DELETAR" para confirmar a exclusão:');
  if (confirmacaoFinal === 'DELETAR') {
    fetch('deletar_ordem.php', {
      method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'},
      body:'id='+encodeURIComponent(id)
    }).then(r=>r.text()).then(tx=>{
      if (tx.trim()==='sucesso'){ linhaParaDeletar.remove(); alert('Ordem deletada!'); }
      else alert('Erro ao deletar: '+tx);
    });
  }
  fecharModalConfirmacao();
}

/* --------- PDF --------- */
function gerarPDF(botao){
  const { jsPDF } = window.jspdf;
  const linha = botao.closest('tr');
  const doc = new jsPDF();
  doc.setFont('helvetica'); doc.setFontSize(18); doc.text('ORDEM DE SERVIÇO', 105, 20, {align:'center'}); doc.setLineWidth(0.5); doc.line(20,25,190,25);
  doc.setFontSize(12); doc.text('DADOS DO CLIENTE', 20, 35);
  doc.setFontSize(10);
  doc.text(`Cliente: ${linha.cells[0].textContent}`, 20, 45);
  doc.text(`CPF: ${linha.cells[1].textContent}`, 20, 55);
  doc.text(`Telefone: ${linha.cells[2].textContent}`, 20, 65);
  doc.text(`Email: ${linha.cells[3].textContent}`, 20, 75);
  doc.setFontSize(12); doc.text('DADOS DO SERVIÇO', 20, 90);
  doc.setFontSize(10);
  doc.text(`Aparelho: ${linha.cells[4].textContent}`, 20, 100);
  doc.text(`Serviço: ${linha.cells[5].textContent}`, 20, 110);
  doc.text(`Data de Entrada: ${linha.cells[6].textContent}`, 20, 120);
  doc.text(`Data de Saída: ${linha.cells[7].textContent}`, 20, 130);
  doc.text(`Valor: ${linha.cells[8].textContent}`, 20, 140);
  doc.text(`Forma de Pagamento: ${linha.cells[9].textContent}`, 20, 150);
  doc.text(`Status: ${linha.cells[10].textContent}`, 20, 160);

  // Peças (se existirem na UI)
  const pecasData = linha.getAttribute('data-pecas');
  const pecas = pecasData && pecasData !== '[]' ? JSON.parse(pecasData) : [];
  if (pecas.length){
    doc.setFontSize(12); doc.text('PEÇAS UTILIZADAS', 20, 175);
    doc.setFontSize(10); let y=185;
    pecas.forEach(p=>{ doc.text(`- ${p.nome} (${p.codigo}) - Qtd: ${p.quantidade}`, 20, y); y+=8; });
  }
  doc.setLineWidth(0.2); doc.line(20, 260, 190, 260);
  doc.setFontSize(8);
  doc.text('Assinatura do Responsável: _________________________________________', 20, 270);
  doc.text('Assinatura do Cliente: ____________________________________________', 20, 280);
  doc.save(`Ordem_Servico_${linha.cells[0].textContent.replace(/\s+/g,'_')}.pdf`);
}

// Fechar modal clicando fora
window.onclick = function(e){
  const m1 = document.getElementById('modal-edicao');
  const m2 = document.getElementById('modal-confirmacao');
  if (e.target === m1) fecharModal();
  if (e.target === m2) fecharModalConfirmacao();
};

// Menu ativo
document.querySelectorAll('.sidebar .menu li a').forEach(a=>{
  if (a.getAttribute('href') === location.pathname.split('/').pop()) a.classList.add('active');
});
</script>
</body>
</html>
