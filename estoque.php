<?php
session_start();
require_once 'conexao.php'; // conexão PDO

// Verifica permissão de acesso
if (!isset($_SESSION['cargo']) || !in_array($_SESSION['cargo'], ['Gerente', 'Atendente'])) {
    echo "Acesso Negado!";
    header("Location: dashboard.php");
    exit();
}

$mensagem = "";

// Função para buscar produtos para listar na página
function buscarProdutos($pdo) {
    $sql = "SELECT p.id_produto, p.nome_produto, p.valor, f.nome_fornecedor, p.cnpj, e.quantidade, p.idestoque
            FROM produto p
            LEFT JOIN fornecedor f ON p.cnpj = f.cnpj
            LEFT JOIN estoque e ON p.idestoque = e.id_estoque
            ORDER BY p.nome_produto";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Buscar fornecedores para popular o select
try {
    $stmt = $pdo->query("SELECT cnpj, nome_fornecedor FROM fornecedor ORDER BY nome_fornecedor");
    $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $fornecedores = [];
    $mensagem = "Erro ao buscar fornecedores: " . $e->getMessage();
}

// ======= TRATAMENTO DE EXCLUSÃO =======
if (isset($_GET['excluir'])) {
    $idExcluir = intval($_GET['excluir']);

    try {
        $pdo->beginTransaction();

        // Buscar idestoque para excluir junto
        $stmt = $pdo->prepare("SELECT idestoque FROM produto WHERE id_produto = ?");
        $stmt->execute([$idExcluir]);
        $idEstoque = $stmt->fetchColumn();

        if ($idEstoque !== false) {
            // Excluir produto
            $stmtDelProd = $pdo->prepare("DELETE FROM produto WHERE id_produto = ?");
            $stmtDelProd->execute([$idExcluir]);

            // Excluir estoque associado
            $stmtDelEst = $pdo->prepare("DELETE FROM estoque WHERE id_estoque = ?");
            $stmtDelEst->execute([$idEstoque]);

            $pdo->commit();

            header("Location: estoque.php?msg=excluido");
            exit();
        } else {
            $pdo->rollBack();
            $mensagem = "Produto não encontrado para exclusão.";
        }
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $mensagem = "Erro ao excluir produto: " . $e->getMessage();
    }
}

// ======= TRATAMENTO DE INSERÇÃO OU ATUALIZAÇÃO =======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto = trim($_POST['produto'] ?? '');
    $quantidade = intval($_POST['quantidade'] ?? 0);
    $cnpj = intval($_POST['cnpj'] ?? 0);
    $valor = floatval(str_replace(',', '.', $_POST['valor'] ?? '0'));
    $id_produto = intval($_POST['id_produto'] ?? 0); // vem no form de edição

    // Validar dados
    if ($produto === '') {
        $mensagem = "Informe o nome do produto.";
    } elseif ($quantidade < 0) {
        $mensagem = "Quantidade não pode ser negativa.";
    } elseif ($valor < 0) {
        $mensagem = "Valor unitário não pode ser negativo.";
    } else {
        // Verificar se fornecedor existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM fornecedor WHERE cnpj = ?");
        $stmt->execute([$cnpj]);
        if ($stmt->fetchColumn() == 0) {
            $mensagem = "Fornecedor inválido selecionado.";
        } else {
            try {
                if ($id_produto > 0) {
                    // ATUALIZAÇÃO
                    $pdo->beginTransaction();

                    // Atualizar estoque
                    $stmtEst = $pdo->prepare("UPDATE estoque SET quantidade = ? WHERE id_estoque = ?");
                    $stmtEst->execute([$quantidade, $_POST['idestoque']]);

                    // Atualizar produto
                    $stmtProd = $pdo->prepare("UPDATE produto SET nome_produto = ?, valor = ?, cnpj = ? WHERE id_produto = ?");
                    $stmtProd->execute([$produto, $valor, $cnpj, $id_produto]);

                    $pdo->commit();

                    $mensagem = "Produto atualizado com sucesso!";
                } else {
                    // INSERÇÃO NOVA
                    $pdo->beginTransaction();

                    // Inserir estoque
                    $stmtEstoque = $pdo->prepare("INSERT INTO estoque (quantidade) VALUES (?)");
                    $stmtEstoque->execute([$quantidade]);
                    $id_estoque = $pdo->lastInsertId();

                    // Inserir produto
                    $stmtProduto = $pdo->prepare("INSERT INTO produto (nome_produto, valor, cnpj, idestoque) VALUES (?, ?, ?, ?)");
                    $stmtProduto->execute([$produto, $valor, $cnpj, $id_estoque]);

                    $pdo->commit();

                    $mensagem = "Produto inserido com sucesso!";
                    $_POST = []; // limpa form
                }
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $mensagem = "Erro ao salvar produto/estoque: " . $e->getMessage();
            }
        }
    }
}

// Atualiza lista de produtos para exibir
$produtos = buscarProdutos($pdo);

// Mensagem após exclusão
if (isset($_GET['msg']) && $_GET['msg'] === 'excluido') {
    $mensagem = "Produto excluído com sucesso!";
}

// Menus por cargo
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

$menuItems = $_SESSION['cargo'] && isset($menus[$_SESSION['cargo']]) ? $menus[$_SESSION['cargo']] : [];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Controle de Estoque</title>
  <link rel="stylesheet" href="css/sidebar.css" />
  <link rel="stylesheet" href="css/form.css" />
  <link rel="stylesheet" href="css/estoque.css" />
  <link rel="icon" href="img/logo.png" type="image/png" />
 
  <script>
    let linhaEditando = null;

    function abrirModalEdicao(botao) {
      linhaEditando = botao.closest('tr');
      const modal = document.getElementById('modal-edicao');

      // Preencher o modal com os dados da linha
      document.getElementById('edit-produto').value = linhaEditando.cells[0].textContent;
      document.getElementById('edit-quantidade').value = linhaEditando.cells[1].textContent;
      document.getElementById('edit-valor').value = linhaEditando.cells[2].textContent.replace('R$ ', '').replace(',', '.');
      const fornecedorNome = linhaEditando.cells[3].textContent;
      const selectFornecedor = document.getElementById('edit-fornecedor');
      for (let option of selectFornecedor.options) {
        if (option.text === fornecedorNome) {
          selectFornecedor.value = option.value;
          break;
        }
      }
      document.getElementById('edit-id-produto').value = linhaEditando.getAttribute('data-id-produto');
      document.getElementById('edit-idestoque').value = linhaEditando.getAttribute('data-idestoque');

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
      const produto = document.getElementById('edit-produto').value;
      const quantidade = document.getElementById('edit-quantidade').value;
      const valor = document.getElementById('edit-valor').value;
      const cnpj = document.getElementById('edit-fornecedor').value;
      const id_produto = document.getElementById('edit-id-produto').value;
      const idestoque = document.getElementById('edit-idestoque').value;

      // Validações client-side
      if (!produto) {
        alert('Informe o nome do produto.');
        return;
      }
      if (quantidade < 0) {
        alert('Quantidade não pode ser negativa.');
        return;
      }
      if (valor < 0) {
        alert('Valor unitário não pode ser negativo.');
        return;
      }
      if (!cnpj) {
        alert('Selecione um fornecedor.');
        return;
      }

      // Enviar dados via AJAX
      fetch('estoque.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `produto=${encodeURIComponent(produto)}&quantidade=${quantidade}&valor=${valor}&fornecedor=${cnpj}&id_produto=${id_produto}&idestoque=${idestoque}`
      })
      .then(response => response.text())
      .then(data => {
        // Atualizar a linha na tabela
        linhaEditando.cells[0].textContent = produto;
        linhaEditando.cells[1].textContent = parseInt(quantidade);
        linhaEditando.cells[2].textContent = 'R$ ' + parseFloat(valor).toFixed(2).replace('.', ',');
        const selectFornecedor = document.getElementById('edit-fornecedor');
        linhaEditando.cells[3].textContent = selectFornecedor.options[selectFornecedor.selectedIndex].text;

        alert('Produto atualizado com sucesso!');
        fecharModal();
      })
      .catch(error => {
        alert('Erro ao salvar produto: ' + error);
      });
    }

    let linhaParaDeletar = null;

    function deletarLinha(botao) {
      linhaParaDeletar = botao.closest('tr');
      const produto = linhaParaDeletar.cells[0].textContent;

      document.getElementById('confirmacao-mensagem').textContent = 'Tem certeza que deseja deletar este produto?';
      document.getElementById('confirmacao-detalhes').innerHTML = `<strong>Produto:</strong> ${produto}`;

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
        const id_produto = linhaParaDeletar.getAttribute('data-id-produto');
        window.location.href = 'estoque.php?excluir=' + id_produto;
      } else {
        alert('Exclusão cancelada. O produto não foi deletado.');
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

    // Função para filtrar produtos na tabela
    function filtrarProdutos() {
      const input = document.getElementById('busca-produto').value.toLowerCase();
      const linhas = document.querySelectorAll('.pedidos-table tbody tr');

      linhas.forEach(linha => {
        const nomeProduto = linha.cells[0].textContent.toLowerCase();
        if (nomeProduto.includes(input)) {
          linha.style.display = '';
        } else {
          linha.style.display = 'none';
        }
      });
    }

    // Função para limpar o filtro
    function limparFiltro() {
      document.getElementById('busca-produto').value = '';
      filtrarProdutos();
    }
  </script>
</head>
<body>
  <nav class="sidebar">
    <div class="logo">
      <img src="img/logo.png" alt="Logo do sistema">
    </div>
    <ul class="menu">
      <?php foreach ($menuItems as $item): ?>
        <li><a href="<?php echo $item['href']; ?>" <?php echo $item['href'] === 'estoque.php' ? 'class="active"' : ''; ?>><?php echo $item['icon']; ?> <span><?php echo $item['text']; ?></span></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>
  <div class="form-container">
    <h2>➕ Cadastrar Produto</h2>

    <?php if ($mensagem): ?>
      <p class="<?= (strpos($mensagem, 'sucesso') !== false) ? 'mensagem-sucesso' : 'mensagem-erro' ?>">
        <?= htmlspecialchars($mensagem) ?>
      </p>
    <?php endif; ?>

    <form method="POST" action="estoque.php">
      <div class="form-group">
        <label for="produto">Nome do Produto</label>
        <input type="text" id="produto" name="produto" class="form-input" placeholder="Ex: Tela Samsung A10" required />
      </div>
      <div class="form-group">
        <label for="quantidade">Quantidade</label>
        <input type="number" id="quantidade" name="quantidade" class="form-input" min="0" required />
      </div>
      <div class="form-group">
        <label for="valor">Valor Unitário (R$)</label>
        <input type="text" id="valor" name="valor" class="form-input" placeholder="Ex: 120.50" required />
      </div>
      <div class="form-group">
        <label for="fornecedor">Fornecedor</label>
        <select id="fornecedor" name="fornecedor" class="form-input" required>
          <option value="">Selecione o fornecedor</option>
          <?php foreach ($fornecedores as $forn): ?>
            <option value="<?= $forn['cnpj'] ?>"><?= htmlspecialchars($forn['nome_fornecedor']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-buttons">
        <button type="submit" class="btn-salvar">Cadastrar Produto</button>
      </div>
    </form>

    <!-- Filtro de Busca -->
    <form id="filtro-form" style="margin-bottom: 2rem;">
      <div class="form-group">
        <label for="busca-produto">Buscar Produto</label>
        <input type="text" id="busca-produto" class="form-input" placeholder="Digite o nome do produto" oninput="filtrarProdutos()" />
      </div>
    </form>

    <!-- Tabela de produtos -->
    <table class="pedidos-table">
      <thead>
        <tr>
          <th>Nome do Produto</th>
          <th>Quantidade</th>
          <th>Valor Unitário (R$)</th>
          <th>Fornecedor</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($produtos) === 0): ?>
          <tr><td colspan="5">Nenhum produto cadastrado.</td></tr>
        <?php else: ?>
          <?php foreach ($produtos as $prod): ?>
            <tr data-id-produto="<?= $prod['id_produto'] ?>" data-idestoque="<?= $prod['idestoque'] ?>">
              <td><?= htmlspecialchars($prod['nome_produto']) ?></td>
              <td><?= intval($prod['quantidade']) ?></td>
              <td><?= number_format($prod['valor'], 2, ',', '.') ?></td>
              <td><?= htmlspecialchars($prod['nome_fornecedor'] ?? '-') ?></td>
              <td class="actions">
                <button class="action-btn edit-btn" onclick="abrirModalEdicao(this)">✏️<span class="tooltip-text">Editar</span></button>
                <button class="action-btn delete-btn" onclick="deletarLinha(this)">🗑️<span class="tooltip-text">Deletar</span></button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Modal de Edição -->
  <div id="modal-edicao" class="modal">
    <div class="modal-content">
      <span class="close-btn" onclick="fecharModal()">&times;</span>
      <h2 style="color: #03dac6;">Editar Produto</h2>
      <form id="form-edicao">
        <div class="form-group">
          <label for="edit-produto">Nome do Produto</label>
          <input type="text" id="edit-produto" class="form-input" required />
        </div>
        <div class="form-group">
          <label for="edit-quantidade">Quantidade</label>
          <input type="number" id="edit-quantidade" class="form-input" min="0" required />
        </div>
        <div class="form-group">
          <label for="edit-valor">Valor Unitário (R$)</label>
          <input type="text" id="edit-valor" class="form-input" required />
        </div>
        <div class="form-group">
          <label for="edit-fornecedor">Fornecedor</label>
          <select id="edit-fornecedor" class="form-input" required>
            <option value="">Selecione o fornecedor</option>
            <?php foreach ($fornecedores as $forn): ?>
              <option value="<?= $forn['cnpj'] ?>"><?= htmlspecialchars($forn['nome_fornecedor']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <input type="hidden" id="edit-id-produto" name="id_produto" />
        <input type="hidden" id="edit-idestoque" name="idestoque" />
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
      <p id="confirmacao-mensagem">Tem certeza que deseja deletar este produto?</p>
      <p id="confirmacao-detalhes" style="font-size: 0.9em; color: #aaa; margin-bottom: 20px;"></p>
      <div class="form-buttons">
        <button type="button" class="btn-cancelar" onclick="fecharModalConfirmacao()">Cancelar</button>
        <button type="button" class="btn-salvar" onclick="confirmarExclusao()">Sim, Deletar</button>
      </div>
    </div>
  </div>
</body>
</html>