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

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pedidos</title>
  <link rel="stylesheet" href="css/sidebar.css"/>
  <link rel="stylesheet" href="css/form.css" />
  <link rel="stylesheet" href="css/pedidos.css" />
  <link rel="icon" href="img/logo.png" type="image/png">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <style>
    /* Adicionando estilo para a mini aba de peças */
    .mini-aba {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: #1e1e1e;
      padding: 20px;
      border-radius: 8px;
      z-index: 1000;
      width: 400px;
      box-shadow: 0 0 20px rgba(0,0,0,0.5);
    }
    
    .mini-aba h3 {
      margin-top: 0;
      color: #03dac6;
    }
    
    .lista-pecas {
      max-height: 200px;
      overflow-y: auto;
      margin: 10px 0;
    }
    
    .peca-item {
      padding: 8px;
      margin: 5px 0;
      background: #333;
      border-radius: 4px;
      cursor: pointer;
    }
    
    .peca-item:hover {
      background: #444;
    }
    
    .peca-adicionada {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px;
      margin: 5px 0;
      background: #333;
      border-radius: 4px;
    }
    
    .peca-info {
      flex-grow: 1;
    }
    
    .peca-quantidade {
      width: 50px;
      margin: 0 10px;
      padding: 3px;
      text-align: center;
      border-radius: 4px;
      border: none;
      background: #444;
      color: white;
    }
    
    .remove-peca {
      background: #ff4444;
      color: white;
      border: none;
      border-radius: 4px;
      padding: 2px 5px;
      cursor: pointer;
    }
    
    .overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.7);
      z-index: 999;
    }
    
    /* Estilos existentes */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.7);
    }
    
    .modal-content {
      background-color: #1e1e1e;
      margin: 5% auto;
      padding: 20px;
      border-radius: 8px;
      width: 80%;
      max-width: 600px;
      box-shadow: 0 0 20px rgba(0,0,0,0.5);
    }
    
    .close-btn {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }
    
    .close-btn:hover {
      color: #fff;
    }
    
    .form-group {
      margin-bottom: 15px;
    }
    
    .form-input {
      width: 100%;
      padding: 8px;
      border-radius: 4px;
      border: none;
      background: #333;
      color: white;
    }
    
    .form-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 20px;
    }
    
    .btn-salvar {
      padding: 8px 16px;
      background-color: #03dac6;
      border: none;
      border-radius: 4px;
      color: #000;
      cursor: pointer;
    }
    
    .btn-cancelar {
      padding: 8px 16px;
      background-color: #444;
      border: none;
      border-radius: 4px;
      color: #fff;
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

  <div class="form-container">
    <h2>📦 Lista de Ordem de serviço</h2>

    <!-- Filtro de Busca -->
    <form id="filtro-form" style="margin-bottom: 2rem;">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <input type="text" name="nome" placeholder="Nome" style="padding: 0.5rem; border-radius: 8px; border: none;">
        <input type="text" name="cpf_cnpj" id="cpf_cnpj" placeholder="CPF/CNPJ" style="padding: 0.5rem; border-radius: 8px; border: none;">
        <input type="text" name="telefone" id="telefone" placeholder="Telefone" style="padding: 0.5rem; border-radius: 8px; border: none;" maxlength="15">
        <input type="email" name="email" placeholder="E-mail" style="padding: 0.5rem; border-radius: 8px; border: none;">
        <select name="tipo" style="padding: 0.5rem; border-radius: 8px; border: none;">
          <option value="">Tipo de cliente</option>
          <option value="pf">Pessoa Física</option>
          <option value="pj">Pessoa Jurídica</option>
        </select>
        <input type="date" name="data_entrada" style="padding: 0.5rem; border-radius: 8px; border: none;">
      </div>

      <div style="margin-top: 1rem; display: flex; gap: 1rem; flex-wrap: wrap;">
        <button type="submit" style="padding: 0.6rem 1.2rem; background-color: #03dac6; border: none; border-radius: 8px; color: #000; cursor: pointer;">Buscar</button>
        <button type="reset" style="padding: 0.6rem 1.2rem; background-color: #444; border: none; border-radius: 8px; color: #fff; cursor: pointer;">Limpar</button>
      </div>
    </form>

    <!-- Tabela -->
    <table class="pedidos-table">
      <thead>
        <tr>
          <th>Cliente</th>
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
        <tr data-pecas='[]'>
          <td>João Silva</td>
          <td>iPhone 11</td>
          <td>Troca de tela</td>
          <td>09/04/2025</td>
          <td>-</td>
          <td>R$ 250,00</td>
          <td>Cartão</td>
          <td class="status pendente">Pendente</td>
          <td class="actions">
            <button class="action-btn edit-btn" onclick="abrirModalEdicao(this)">✏️<span class="tooltip-text">Editar</span></button>
            <button class="action-btn pdf-btn" onclick="gerarPDF(this)">📄<span class="tooltip-text">Gerar PDF</span></button>
            <button class="action-btn delete-btn" onclick="deletarLinha(this)">🗑️<span class="tooltip-text">Deletar</span></button>
          </td>
        </tr>
        <tr data-pecas='[]'>
          <td>Maria Oliveira</td>
          <td>Samsung A21s</td>
          <td>Reparo no botão</td>
          <td>08/04/2025</td>
          <td>-</td>
          <td>R$ 120,00</td>
          <td>Pix</td>
          <td class="status andamento">Em andamento</td>
          <td class="actions">
            <button class="action-btn edit-btn" onclick="abrirModalEdicao(this)">✏️<span class="tooltip-text">Editar</span></button>
            <button class="action-btn pdf-btn" onclick="gerarPDF(this)">📄<span class="tooltip-text">Gerar PDF</span></button>
            <button class="action-btn delete-btn" onclick="deletarLinha(this)">🗑️<span class="tooltip-text">Deletar</span></button>
          </td>
        </tr>
        <tr data-pecas='[]'>
          <td>Lucas Costa</td>
          <td>Motorola G9</td>
          <td>Troca de bateria</td>
          <td>07/04/2025</td>
          <td>10/04/2025</td>
          <td>R$ 180,00</td>
          <td>Dinheiro</td>
          <td class="status concluido">Concluído</td>
          <td class="actions">
            <button class="action-btn edit-btn" onclick="abrirModalEdicao(this)">✏️<span class="tooltip-text">Editar</span></button>
            <button class="action-btn pdf-btn" onclick="gerarPDF(this)">📄<span class="tooltip-text">Gerar PDF</span></button>
            <button class="action-btn delete-btn" onclick="deletarLinha(this)">🗑️<span class="tooltip-text">Deletar</span></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

<!-- Modal de Edição -->
<div id="modal-edicao" class="modal">
  <div class="modal-content">
    <span class="close-btn" onclick="fecharModal()">&times;</span>
    <h2 style="color: #03dac6; margin-bottom: 1.5rem;">Editar Ordem de Serviço</h2>
    <form id="form-edicao">
      <div class="form-group">
        <label for="edit-cliente">Cliente:</label>
        <input type="text" id="edit-cliente" class="form-input">
      </div>
      <div class="form-group">
        <label for="edit-aparelho">Aparelho:</label>
        <input type="text" id="edit-aparelho" class="form-input">
      </div>
      <div class="form-group">
        <label for="edit-servico">Serviço:</label>
        <input type="text" id="edit-servico" class="form-input">
      </div>
      
      <!-- Seção de Peças -->
      <div class="form-group">
        <label>Peças utilizadas:</label>
        <div id="lista-pecas-adicionadas" style="margin-bottom: 10px;">
          <!-- As peças adicionadas aparecerão aqui -->
        </div>
        <div style="display: flex; gap: 10px;">
          <button type="button" onclick="abrirMiniAbaPecas()" style="background: #03dac6; color: black; border: none; border-radius: 4px; padding: 5px 10px; cursor: pointer;">Adicionar Peça</button>
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
  <input type="text" id="busca-peca" placeholder="Buscar peça..." style="width: 100%; padding: 8px; margin-bottom: 10px; border-radius: 4px; border: none;">
  
  <div class="lista-pecas">
    <div class="peca-item" onclick="selecionarPeca('Tela LCD iPhone 11', 'TELA-IP11')">Tela LCD iPhone 11</div>
    <div class="peca-item" onclick="selecionarPeca('Bateria Motorola G9', 'BAT-MOTG9')">Bateria Motorola G9</div>
    <div class="peca-item" onclick="selecionarPeca('Botão Power Samsung A21s', 'BTN-SAMA21')">Botão Power Samsung A21s</div>
    <div class="peca-item" onclick="selecionarPeca('Conector de Carga Universal', 'CON-USB')">Conector de Carga Universal</div>
    <div class="peca-item" onclick="selecionarPeca('Película Protetora', 'PEL-UNI')">Película Protetora</div>
  </div>
  
  <div style="margin-top: 15px; display: flex; align-items: center; justify-content: space-between;">
    <div style="display: flex; align-items: center;">
      <label style="margin-right: 10px;">Quantidade:</label>
      <input type="number" id="peca-quantidade" min="1" value="1" style="width: 60px; padding: 5px; border-radius: 4px; border: none; background: #444; color: white;">
    </div>
    <button type="button" onclick="adicionarPeca()" style="background: #03dac6; color: black; border: none; border-radius: 4px; padding: 5px 10px; cursor: pointer;">Adicionar</button>
  </div>
  
  <button type="button" onclick="fecharMiniAbaPecas()" style="margin-top: 10px; background: #444; color: white; border: none; border-radius: 4px; padding: 5px 10px; cursor: pointer;">Cancelar</button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
  // Máscara para CPF/CNPJ
  const input = document.getElementById('cpf_cnpj');

  input.addEventListener('input', function () {
    let value = input.value.replace(/\D/g, '');

    // Limita para no máximo 14 dígitos (CNPJ)
    value = value.slice(0, 14);

    if (value.length <= 11) {
      // CPF: 000.000.000-00
      value = value.replace(/(\d{3})(\d)/, '$1.$2');
      value = value.replace(/(\d{3})(\d)/, '$1.$2');
      value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    } else {
      // CNPJ: 00.000.000/0000-00
      value = value.replace(/^(\d{2})(\d)/, '$1.$2');
      value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
      value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
      value = value.replace(/(\d{4})(\d)/, '$1-$2');
    }

    input.value = value;
  });

  // Máscara para telefone (fixo ou celular)
  const telefoneInput = document.getElementById('telefone');
  
  telefoneInput.addEventListener('input', function() {
    // Remove tudo que não é dígito
    let value = this.value.replace(/\D/g, '');
    
    // Limita o tamanho (11 dígitos para celular com DDD)
    value = value.slice(0, 11);
    
    // Aplica a máscara
    if (value.length > 0) {
      // Formato: (XX) XXXX-XXXX para telefone fixo (10 dígitos)
      if (value.length <= 10) {
        value = value.replace(/(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{4})(\d)/, '$1-$2');
      } 
      // Formato: (XX) XXXXX-XXXX para celular (11 dígitos)
      else {
        value = value.replace(/(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
      }
    }
    
    this.value = value;
  });

  // Validação para permitir apenas números e controlar o backspace
  telefoneInput.addEventListener('keydown', function(e) {
    // Permite: backspace, delete, tab, escape, enter
    if ([46, 8, 9, 27, 13].includes(e.keyCode) || 
        // Permite: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
        (e.keyCode == 65 && e.ctrlKey === true) || 
        (e.keyCode == 67 && e.ctrlKey === true) || 
        (e.keyCode == 86 && e.ctrlKey === true) || 
        (e.keyCode == 88 && e.ctrlKey === true) ||
        // Permite: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
          return;
    }
    // Bloqueia se não for número
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
      e.preventDefault();
    }
  });

  // Variáveis globais
  let linhaEditando = null;
  let pecaSelecionada = null;

  // Funções para manipulação de peças
  function abrirMiniAbaPecas() {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('mini-aba-pecas').style.display = 'block';
    document.getElementById('peca-quantidade').value = 1;
    pecaSelecionada = null;
  }
  
  function fecharMiniAbaPecas() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('mini-aba-pecas').style.display = 'none';
  }
  
  function selecionarPeca(nome, codigo) {
    pecaSelecionada = { nome, codigo };
    // Destacar a peça selecionada
    const pecas = document.querySelectorAll('.peca-item');
    pecas.forEach(p => p.style.background = '#333');
    event.target.style.background = '#03dac6';
    event.target.style.color = '#000';
  }
  
  function adicionarPeca() {
    if (!pecaSelecionada || !linhaEditando) return;
    
    const quantidade = parseInt(document.getElementById('peca-quantidade').value) || 1;
    const peca = {
      ...pecaSelecionada,
      quantidade
    };
    
    // Adicionar a peça à lista no modal
    const listaPecas = document.getElementById('lista-pecas-adicionadas');
    const pecaDiv = document.createElement('div');
    pecaDiv.className = 'peca-adicionada';
    pecaDiv.dataset.codigo = peca.codigo;
    
    pecaDiv.innerHTML = `
      <div class="peca-info">${peca.nome} (${peca.codigo})</div>
      <input type="number" class="peca-quantidade" value="${peca.quantidade}" min="1">
      <button class="remove-peca" onclick="removerPeca(this)">X</button>
    `;
    
    listaPecas.appendChild(pecaDiv);
    fecharMiniAbaPecas();
  }
  
  function removerPeca(botao) {
    botao.closest('.peca-adicionada').remove();
  }

  function alterarStatus(botao) {
    const statusTd = botao.closest('tr').querySelector('.status');
    const estados = ['pendente', 'andamento', 'concluido', 'cancelado'];
    const textos = ['Pendente', 'Em andamento', 'Concluído', 'Cancelado'];

    let estadoAtual = estados.findIndex(e => statusTd.classList.contains(e));
    let proximo = (estadoAtual + 1) % estados.length;

    estados.forEach(e => statusTd.classList.remove(e));
    statusTd.classList.add(estados[proximo]);
    statusTd.textContent = textos[proximo];
  }

  function abrirModalEdicao(botao) {
    linhaEditando = botao.closest('tr');
    const modal = document.getElementById('modal-edicao');
    
    // Preencher o modal com os dados da linha
    document.getElementById('edit-cliente').value = linhaEditando.cells[0].textContent;
    document.getElementById('edit-aparelho').value = linhaEditando.cells[1].textContent;
    document.getElementById('edit-servico').value = linhaEditando.cells[2].textContent;
    
    // Converter datas para formato input date (YYYY-MM-DD)
    const dataEntrada = converterDataParaInput(linhaEditando.cells[3].textContent);
    document.getElementById('edit-data-entrada').value = dataEntrada;
    
    const dataSaida = linhaEditando.cells[4].textContent === '-' ? '' : converterDataParaInput(linhaEditando.cells[4].textContent);
    document.getElementById('edit-data-saida').value = dataSaida;
    
    document.getElementById('edit-valor').value = linhaEditando.cells[5].textContent.replace('R$ ', '');
    document.getElementById('edit-pagamento').value = linhaEditando.cells[6].textContent;
    document.getElementById('edit-status').value = linhaEditando.cells[7].textContent;
    
    // Carregar peças existentes
    const listaPecas = document.getElementById('lista-pecas-adicionadas');
    listaPecas.innerHTML = '';
    
    const pecasData = linhaEditando.getAttribute('data-pecas');
    if (pecasData && pecasData !== '[]') {
      const pecas = JSON.parse(pecasData);
      pecas.forEach(peca => {
        const pecaDiv = document.createElement('div');
        pecaDiv.className = 'peca-adicionada';
        pecaDiv.dataset.codigo = peca.codigo;
        
        pecaDiv.innerHTML = `
          <div class="peca-info">${peca.nome} (${peca.codigo})</div>
          <input type="number" class="peca-quantidade" value="${peca.quantidade}" min="1">
          <button class="remove-peca" onclick="removerPeca(this)">X</button>
        `;
        
        listaPecas.appendChild(pecaDiv);
      });
    }
    
    // Mostrar o modal
    modal.style.display = 'block';
  }

  function converterDataParaInput(data) {
    if (data === '-' || !data) return '';
    const partes = data.split('/');
    return `${partes[2]}-${partes[1]}-${partes[0]}`;
  }

  function converterInputParaData(input) {
    if (!input) return '-';
    const date = new Date(input);
    return date.toLocaleDateString('pt-BR');
  }

  function fecharModal() {
    document.getElementById('modal-edicao').style.display = 'none';
  }

  function salvarEdicao() {
    if (!linhaEditando) return;
    
    // Atualizar os dados da linha com os valores do formulário
    linhaEditando.cells[0].textContent = document.getElementById('edit-cliente').value;
    linhaEditando.cells[1].textContent = document.getElementById('edit-aparelho').value;
    linhaEditando.cells[2].textContent = document.getElementById('edit-servico').value;
    linhaEditando.cells[3].textContent = converterInputParaData(document.getElementById('edit-data-entrada').value);
    linhaEditando.cells[4].textContent = converterInputParaData(document.getElementById('edit-data-saida').value);
    linhaEditando.cells[5].textContent = 'R$ ' + document.getElementById('edit-valor').value;
    linhaEditando.cells[6].textContent = document.getElementById('edit-pagamento').value;
    
    // Atualizar status (incluindo a classe CSS)
    const novoStatus = document.getElementById('edit-status').value;
    const statusTd = linhaEditando.cells[7];
    statusTd.textContent = novoStatus;
    
    // Remover todas as classes de status e adicionar a correta
    statusTd.className = 'status';
    if (novoStatus === 'Pendente') statusTd.classList.add('pendente');
    else if (novoStatus === 'Em andamento') statusTd.classList.add('andamento');
    else if (novoStatus === 'Concluído') statusTd.classList.add('concluido');
    else if (novoStatus === 'Cancelado') statusTd.classList.add('cancelado');
    
    // Atualizar peças
    const pecasAdicionadas = document.querySelectorAll('#lista-pecas-adicionadas .peca-adicionada');
    const pecasArray = [];
    
    pecasAdicionadas.forEach(pecaDiv => {
      const nome = pecaDiv.querySelector('.peca-info').textContent.split(' (')[0];
      const codigo = pecaDiv.dataset.codigo;
      const quantidade = parseInt(pecaDiv.querySelector('.peca-quantidade').value) || 1;
      
      pecasArray.push({
        nome,
        codigo,
        quantidade
      });
    });
    
    linhaEditando.setAttribute('data-pecas', JSON.stringify(pecasArray));
    
    fecharModal();
  }

  function gerarPDF(botao) {
    const { jsPDF } = window.jspdf;
    const linha = botao.closest('tr');
    
    // Obter as peças atribuídas (se existirem)
    const pecasData = linha.getAttribute('data-pecas');
    const pecas = pecasData && pecasData !== '[]' ? JSON.parse(pecasData) : [];
    
    const doc = new jsPDF();
    
    // Configurações do documento
    doc.setFont('helvetica');
    doc.setFontSize(18);
    doc.setTextColor(0, 0, 0);
    
    // Cabeçalho
    doc.text('ORDEM DE SERVIÇO', 105, 20, { align: 'center' });
    doc.setLineWidth(0.5);
    doc.line(20, 25, 190, 25);
    
    // Informações do cliente
    doc.setFontSize(12);
    doc.text('DADOS DO CLIENTE', 20, 35);
    
    doc.setFontSize(10);
    doc.text(`Cliente: ${linha.cells[0].textContent}`, 20, 45);
    doc.text(`Aparelho: ${linha.cells[1].textContent}`, 20, 55);
    doc.text(`Serviço: ${linha.cells[2].textContent}`, 20, 65);
    
    // Informações do serviço
    doc.setFontSize(12);
    doc.text('DADOS DO SERVIÇO', 20, 80);
    
    doc.setFontSize(10);
    doc.text(`Data de Entrada: ${linha.cells[3].textContent}`, 20, 90);
    doc.text(`Data de Saída: ${linha.cells[4].textContent === '-' ? 'Não concluído' : linha.cells[4].textContent}`, 20, 100);
    doc.text(`Valor: ${linha.cells[5].textContent}`, 20, 110);
    doc.text(`Forma de Pagamento: ${linha.cells[6].textContent}`, 20, 120);
    doc.text(`Status: ${linha.cells[7].textContent}`, 20, 130);
    
    // Adicionando informações das peças se existirem
    if (pecas.length > 0) {
      doc.setFontSize(12);
      doc.text('PEÇAS UTILIZADAS', 20, 145);
      
      doc.setFontSize(10);
      let yPosition = 155;
      pecas.forEach(peca => {
        doc.text(`- ${peca.nome} (${peca.codigo}) - Qtd: ${peca.quantidade}`, 20, yPosition);
        yPosition += 10;
      });
    }
    
    // Rodapé
    doc.setLineWidth(0.2);
    doc.line(20, 260, 190, 260);
    doc.setFontSize(8);
    doc.text('Assinatura do Responsável: _________________________________________', 20, 270);
    doc.text('Assinatura do Cliente: ____________________________________________', 20, 280);
    
    // Salvar o PDF
    doc.save(`Ordem_Servico_${linha.cells[0].textContent.replace(/\s+/g, '_')}.pdf`);
  }

  // Fechar o modal se clicar fora dele
  window.onclick = function(event) {
    const modal = document.getElementById('modal-edicao');
    if (event.target == modal) {
      fecharModal();
    }
  };

  // Menu ativo
  const links = document.querySelectorAll('.sidebar .menu li a');
  const currentPage = window.location.pathname.split('/').pop();

  links.forEach(link => {
    if (link.getAttribute('href') === currentPage) {
      link.classList.add('active');
    }
  });

  let linhaParaDeletar = null;

function deletarLinha(botao) {
  linhaParaDeletar = botao.closest('tr');
  const cliente = linhaParaDeletar.cells[0].textContent;
  const aparelho = linhaParaDeletar.cells[1].textContent;
  const servico = linhaParaDeletar.cells[2].textContent;
  
  document.getElementById('confirmacao-mensagem').textContent = 
    'Tem certeza que deseja deletar esta ordem de serviço?';
  
  document.getElementById('confirmacao-detalhes').innerHTML = 
    `<strong>Cliente:</strong> ${cliente}<br>
     <strong>Aparelho:</strong> ${aparelho}<br>
     <strong>Serviço:</strong> ${servico}`;
  
  // Mostrar o modal de confirmação
  document.getElementById('modal-confirmacao').style.display = 'block';
}

function fecharModalConfirmacao() {
  document.getElementById('modal-confirmacao').style.display = 'none';
  linhaParaDeletar = null;
}

function confirmarExclusao() {
  if (!linhaParaDeletar) return;
  
  // Segunda confirmação - mais rigorosa
  const confirmacaoFinal = prompt('Digite "DELETAR" para confirmar a exclusão:');
  
  if (confirmacaoFinal === 'DELETAR') {
    linhaParaDeletar.remove();
    
    // Aqui você pode adicionar a chamada AJAX para deletar do banco de dados
    // fetch(...) como no exemplo anterior
    
    alert('Ordem de serviço deletada com sucesso!');
  } else {
    alert('Exclusão cancelada. A ordem de serviço não foi deletada.');
  }
  
  fecharModalConfirmacao();
}
</script>

<!-- Modal de Confirmação de Exclusão -->
<div id="modal-confirmacao" class="modal">
  <div class="modal-content" style="max-width: 400px;">
    <span class="close-btn" onclick="fecharModalConfirmacao()">&times;</span>
    <h2 style="color: #ff4444; margin-bottom: 1.5rem;">Confirmar Exclusão</h2>
    <p id="confirmacao-mensagem">Tem certeza que deseja deletar esta ordem de serviço?</p>
    <p id="confirmacao-detalhes" style="font-size: 0.9em; color: #aaa; margin-bottom: 20px;"></p>
    
    <div class="form-buttons">
      <button type="button" class="btn-cancelar" onclick="fecharModalConfirmacao()">Cancelar</button>
      <button type="button" class="btn-salvar" style="background-color: #ff4444;" onclick="confirmarExclusao()">Sim, Deletar</button>
    </div>
  </div>
</div>
</body>
</html>