<?php
session_start();
require_once 'conexao.php'; // conexão PDO

// Verifica permissão de acesso
if (!isset($_SESSION['cargo']) || !in_array($_SESSION['cargo'], ['Gerente', 'Atendente'])) {
    echo "Acesso Negado!";
    exit();
}

$mensagem = "";
$modoEdicao = false;
$editarProduto = null;

// Função para buscar produtos para listar na página
function buscarProdutos($pdo) {
    $sql = "SELECT p.id_produto, p.nome_produto, p.valor, f.nome_fornecedor, p.idfornecedor, e.quantidade, p.idestoque
            FROM produto p
            LEFT JOIN fornecedor f ON p.idfornecedor = f.id_fornecedor
            LEFT JOIN estoque e ON p.idestoque = e.id_estoque
            ORDER BY p.nome_produto";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Buscar fornecedores para popular o select
try {
    $stmt = $pdo->query("SELECT id_fornecedor, nome_fornecedor FROM fornecedor ORDER BY nome_fornecedor");
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

// ======= TRATAMENTO DE INÍCIO DA EDIÇÃO =======
if (isset($_GET['editar'])) {
    $idEditar = intval($_GET['editar']);
    $stmt = $pdo->prepare("SELECT p.id_produto, p.nome_produto, p.valor, p.idfornecedor, e.quantidade, p.idestoque
                           FROM produto p
                           LEFT JOIN estoque e ON p.idestoque = e.id_estoque
                           WHERE p.id_produto = ?");
    $stmt->execute([$idEditar]);
    $editarProduto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($editarProduto) {
        $modoEdicao = true;
    } else {
        $mensagem = "Produto para edição não encontrado.";
    }
}

// ======= TRATAMENTO DE INSERÇÃO OU ATUALIZAÇÃO =======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto = trim($_POST['produto'] ?? '');
    $quantidade = intval($_POST['quantidade'] ?? 0);
    $id_fornecedor = intval($_POST['fornecedor'] ?? 0);
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
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM fornecedor WHERE id_fornecedor = ?");
        $stmt->execute([$id_fornecedor]);
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
                    $stmtProd = $pdo->prepare("UPDATE produto SET nome_produto = ?, valor = ?, idfornecedor = ? WHERE id_produto = ?");
                    $stmtProd->execute([$produto, $valor, $id_fornecedor, $id_produto]);

                    $pdo->commit();

                    $mensagem = "Produto atualizado com sucesso!";
                    $modoEdicao = false; // sai do modo edição
                } else {
                    // INSERÇÃO NOVA
                    $pdo->beginTransaction();

                    // Inserir estoque
                    $stmtEstoque = $pdo->prepare("INSERT INTO estoque (quantidade) VALUES (?)");
                    $stmtEstoque->execute([$quantidade]);
                    $id_estoque = $pdo->lastInsertId();

                    // Inserir produto
                    $stmtProduto = $pdo->prepare("INSERT INTO produto (nome_produto, valor, idfornecedor, idestoque) VALUES (?, ?, ?, ?)");
                    $stmtProduto->execute([$produto, $valor, $id_fornecedor, $id_estoque]);

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
  <link rel="stylesheet" href="css/estoque.css" />
  <link rel="stylesheet" href="css/form.css" />
  <link rel="icon" href="img/logo.png" type="image/png" />
  
  <script>
    function confirmarExclusao(id, nome) {
      if (confirm('Tem certeza que deseja excluir o produto "' + nome + '"?')) {
        window.location.href = 'estoque.php?excluir=' + id;
      }
    }
  </script>
</head>
<body>
  <nav class="sidebar">
    <div class="logo">
      <img src="img/logo.png" alt="Logo do sistema" />
    </div>
    <ul class="menu">
      <?php foreach ($menuItems as $item): ?>
        <li><a href="<?= htmlspecialchars($item['href']) ?>"><?= $item['icon'] ?> <span><?= htmlspecialchars($item['text']) ?></span></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <div class="form-container">
    <h2><?= $modoEdicao ? "✏️ Editar Produto" : "➕ Cadastrar Produto" ?></h2>

    <?php if ($mensagem): ?>
      <p class="<?= (strpos($mensagem, 'sucesso') !== false) ? 'mensagem-sucesso' : 'mensagem-erro' ?>">
        <?= htmlspecialchars($mensagem) ?>
      </p>
    <?php endif; ?>

    <form method="POST" action="estoque.php">
      <input type="hidden" name="id_produto" value="<?= $modoEdicao ? htmlspecialchars($editarProduto['id_produto']) : '' ?>" />
      <input type="hidden" name="idestoque" value="<?= $modoEdicao ? htmlspecialchars($editarProduto['idestoque']) : '' ?>" />

      <label for="produto">Nome do Produto</label>
      <input type="text" id="produto" name="produto" placeholder="Ex: Tela Samsung A10" required
        value="<?= $modoEdicao ? htmlspecialchars($editarProduto['nome_produto']) : '' ?>" />

      <label for="quantidade">Quantidade</label>
      <input type="number" id="quantidade" name="quantidade" min="0" required
        value="<?= $modoEdicao ? htmlspecialchars($editarProduto['quantidade']) : '' ?>" />

      <label for="valor">Valor Unitário (R$)</label>
      <input type="text" id="valor" name="valor" placeholder="Ex: 120.50" required
        value="<?= $modoEdicao ? htmlspecialchars(number_format($editarProduto['valor'], 2, ',', '.')) : '' ?>" />

      <label for="fornecedor">Fornecedor</label>
      <select id="fornecedor" name="fornecedor" required>
        <option value="">Selecione o fornecedor</option>
        <?php foreach ($fornecedores as $forn): ?>
          <option value="<?= $forn['id_fornecedor'] ?>"
            <?= ($modoEdicao && $forn['id_fornecedor'] == $editarProduto['idfornecedor']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($forn['nome_fornecedor']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <button type="submit"><?= $modoEdicao ? "Atualizar Produto" : "Cadastrar Produto" ?></button>
      <?php if ($modoEdicao): ?>
        <a href="estoque.php" style="margin-left: 15px; color: #555; text-decoration:none;">Cancelar edição</a>
      <?php endif; ?>
    </form>

    <!-- Tabela de produtos -->
    <table>
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
            <tr>
              <td><?= htmlspecialchars($prod['nome_produto']) ?></td>
              <td><?= intval($prod['quantidade']) ?></td>
              <td><?= number_format($prod['valor'], 2, ',', '.') ?></td>
              <td><?= htmlspecialchars($prod['nome_fornecedor'] ?? '-') ?></td>
              <td>
                <a href="estoque.php?editar=<?= $prod['id_produto'] ?>" class="acao-btn editar">✏️ Editar</a>
                <a href="javascript:void(0)" class="acao-btn excluir" onclick="confirmarExclusao(<?= $prod['id_produto'] ?>, '<?= addslashes($prod['nome_produto']) ?>')">🗑️ Excluir</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</body>
</html>
