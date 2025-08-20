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

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Controle de Estoque</title>
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/estoque.css">
  <link rel="stylesheet" href="css/form.css" />
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
  <div class="form-container fade-in">
    <h2 class="fade-in delay-1">📦 Controle de Estoque</h2>

    <form class="fade-in delay-2" id="estoqueForm">
      <label for="produto">Nome do Produto</label>
      <input type="text" id="produto" name="produto" placeholder="Ex: Tela Samsung A10" required>

      <label for="quantidade">Quantidade</label>
      <input type="number" id="quantidade" name="quantidade" placeholder="Ex: 10" min="0" required>

      <label for="fornecedor">Fornecedor</label>
      <input type="text" id="fornecedor" name="fornecedor" placeholder="Ex: TechParts" required>

      <label for="valor">Valor Unitário (R$)</label>
      <input type="number" id="valor" name="valor" placeholder="Ex: 80.00" step="0.01" min="0" required>

      <button type="submit" class="btn-login fade-in delay-3">Adicionar ao Estoque</button>
    </form>

    <hr class="fade-in delay-4" style="margin: 2rem 0; border-color: #444;" />

    <input type="text" id="searchInput" placeholder="Buscar produto..." class="search-input fade-in delay-2">

    <h3 class="fade-in delay-4">📋 Itens no Estoque</h3>
    <table class="fade-in delay-5">
      <thead>
        <tr>
          <th>Produto</th>
          <th>Quantidade</th>
          <th>Fornecedor</th>
          <th>Valor (R$)</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Tela iPhone X</td>
          <td>5</td>
          <td>AppleTech</td>
          <td>150.00</td>
          <td>
            <button class="btn-editar">✏️</button>
            <button class="btn-excluir">🗑️</button>
          </td>
        </tr>
        <tr>
          <td>Bateria Samsung A10</td>
          <td>12</td>
          <td>SmartParts</td>
          <td>50.00</td>
          <td>
            <button class="btn-editar">✏️</button>
            <button class="btn-excluir">🗑️</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Modal de Edição -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Editar Produto</h2>
      <form id="editForm">
        <label for="editProduto">Nome do Produto</label>
        <input type="text" id="editProduto" name="produto" required>

        <label for="editQuantidade">Quantidade</label>
        <input type="number" id="editQuantidade" name="quantidade" min="0" required>

        <label for="editFornecedor">Fornecedor</label>
        <input type="text" id="editFornecedor" name="fornecedor" required>

        <label for="editValor">Valor Unitário (R$)</label>
        <input type="number" id="editValor" name="valor" step="0.01" min="0" required>

        <button type="submit" class="btn-login">Salvar Alterações</button>
      </form>
    </div>
  </div>

  <script>
    // Função de busca
    document.getElementById('searchInput').addEventListener('input', function () {
      const searchValue = this.value.toLowerCase();
      const rows = document.querySelectorAll('tbody tr');
      rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const productName = cells[0].textContent.toLowerCase();
        row.style.display = productName.includes(searchValue) ? '' : 'none';
      });
    });

    // Ativa menu atual na sidebar
    const links = document.querySelectorAll('.sidebar .menu li a');
    const currentPage = window.location.pathname.split('/').pop();
    links.forEach(link => {
      if (link.getAttribute('href') === currentPage) {
        link.classList.add('active');
      }
    });

    // Validação do formulário principal
    document.getElementById('estoqueForm').addEventListener('submit', function (event) {
      const quantidade = parseInt(document.getElementById('quantidade').value);
      const valor = parseFloat(document.getElementById('valor').value);
      if (quantidade < 0 || valor < 0) {
        alert("Quantidade e Valor Unitário não podem ser negativos.");
        event.preventDefault();
      }
    });

    // Função do modal de edição
    document.querySelectorAll(".btn-editar").forEach(button => {
      button.addEventListener("click", () => {
        const row = button.closest("tr");
        const cells = row.querySelectorAll("td");

        document.getElementById('editProduto').value = cells[0].textContent;
        document.getElementById('editQuantidade').value = cells[1].textContent;
        document.getElementById('editFornecedor').value = cells[2].textContent;
        document.getElementById('editValor').value = cells[3].textContent;

        const modal = document.getElementById("editModal");
        modal.style.display = "block";

        const span = document.getElementsByClassName("close")[0];
        span.onclick = function () {
          modal.style.display = "none";
        }

        window.onclick = function (event) {
          if (event.target === modal) {
            modal.style.display = "none";
          }
        }

        document.getElementById("editForm").onsubmit = function (e) {
          const editQtd = parseInt(document.getElementById('editQuantidade').value);
          const editVal = parseFloat(document.getElementById('editValor').value);
          if (editQtd < 0 || editVal < 0) {
            alert("Quantidade e Valor Unitário não podem ser negativos.");
            e.preventDefault();
            return;
          }

          e.preventDefault();
          cells[0].textContent = document.getElementById('editProduto').value;
          cells[1].textContent = document.getElementById('editQuantidade').value;
          cells[2].textContent = document.getElementById('editFornecedor').value;
          cells[3].textContent = document.getElementById('editValor').value;
          modal.style.display = "none";
        };
      });
    });

    // Variável para armazenar a linha a ser excluída
let linhaParaExcluir = null;

// Adiciona eventos quando o DOM estiver completamente carregado
document.addEventListener('DOMContentLoaded', function() {
  // Botões de excluir na tabela
  document.querySelectorAll('.btn-excluir').forEach(botao => {
    botao.addEventListener('click', function(e) {
      e.preventDefault();
      linhaParaExcluir = this.closest('tr');
      const produtoNome = linhaParaExcluir.querySelector('td:first-child').textContent;
      
      document.getElementById('produto-excluir-nome').textContent = produtoNome;
      document.getElementById('confirmacao-texto').value = '';
      document.getElementById('confirmar-exclusao').disabled = true;
      
      // Mostra o modal de exclusão
      document.getElementById('modal-excluir').classList.remove('hidden');
    });
  });

  // Validação do texto de confirmação
  document.getElementById('confirmacao-texto').addEventListener('input', function(e) {
    const textoConfirmacao = e.target.value.toUpperCase();
    document.getElementById('confirmar-exclusao').disabled = textoConfirmacao !== 'DELETAR';
  });

  // Confirmação de exclusão
  document.getElementById('confirmar-exclusao').addEventListener('click', function() {
    if (linhaParaExcluir) {
      linhaParaExcluir.remove();
    }
    document.getElementById('modal-excluir').classList.add('hidden');
    linhaParaExcluir = null;
  });

  // Cancelar exclusão
  document.getElementById('cancelar-exclusao').addEventListener('click', function() {
    document.getElementById('modal-excluir').classList.add('hidden');
    linhaParaExcluir = null;
  });

  // Fechar modal ao clicar no X
  document.querySelector('#modal-excluir .close').addEventListener('click', function() {
    document.getElementById('modal-excluir').classList.add('hidden');
    linhaParaExcluir = null;
  });

  // Fechar modal ao clicar fora
  window.addEventListener('click', function(event) {
    if (event.target === document.getElementById('modal-excluir')) {
      document.getElementById('modal-excluir').classList.add('hidden');
      linhaParaExcluir = null;
    }
  });
});
  </script>
  <!-- Modal de confirmação para excluir -->
<div id="modal-excluir" class="modal hidden">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Confirmar Exclusão</h2>
    <p>Tem certeza que deseja excluir o produto <strong id="produto-excluir-nome"></strong>?</p>
    <p>Para confirmar, digite <strong>DELETAR</strong> no campo abaixo:</p>
    <input type="text" id="confirmacao-texto" placeholder="Digite DELETAR" class="search-input">
    <div class="modal-buttons">
      <button id="confirmar-exclusao" class="btn-excluir-modal" disabled>🗑️ Excluir</button>
      <button id="cancelar-exclusao" class="btn-cancelar-modal">↩️ Cancelar</button>
    </div>
  </div>
</div>
</body>
</html>
