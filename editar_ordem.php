<?php
session_start();
require_once 'conexao.php';

// Verificação de permissão
if (!isset($_SESSION['cargo']) || !in_array($_SESSION['cargo'], ["Gerente","Atendente","Tecnico"])) {
    header("Location: dashboard.php");
    exit();
}

// Receber o ID da ordem
$id_ordem = (int)($_GET['id'] ?? 0);
if ($id_ordem <= 0) {
    die("ID da ordem inválido.");
}

// Buscar dados da ordem
try {
    $stmt = $pdo->prepare("SELECT * FROM ordem_serv WHERE id_ordem_serv = :id");
    $stmt->execute([':id' => $id_ordem]);
    $ordem = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ordem) {
        die("Ordem de serviço não encontrada.");
    }
} catch (PDOException $e) {
    die("Erro ao buscar ordem: " . $e->getMessage());
}

// Buscar peças atribuídas a esta ordem
try {
    $sql = "SELECT sp.quantidade, p.id_produto, p.nome_produto, p.valor, e.id_estoque 
            FROM servico_produto sp
            JOIN produto p ON sp.id_produto = p.id_produto
            JOIN estoque e ON p.idestoque = e.id_estoque
            WHERE sp.id_ordem_serv = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id_ordem]);
    $pecasOrdem = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar peças da ordem: " . $e->getMessage());
}


// Processar formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aparelho = $_POST['Aparelho'] ?? '';
    $servico = $_POST['servico'] ?? '';
    $data_entrada = $_POST['data_entrada'] ?? '';
    $data_saida = $_POST['data_saida'] ?? '';
    $valor = $_POST['valor'] ?? '';
    $tipo_pagamento = $_POST['tipo_pagamento'] ?? '';
    $status = $_POST['status'] ?? '';

    try {
        $stmt = $pdo->prepare("
            UPDATE ordem_serv SET
                Aparelho = :aparelho,
                servico = :servico,
                data_entrada = :data_entrada,
                data_saida = :data_saida,
                valor = :valor,
                tipo_pagamento = :tipo_pagamento,
                status = :status
            WHERE id_ordem_serv = :id
        ");
        $stmt->execute([
            ':aparelho' => $aparelho,
            ':servico' => $servico,
            ':data_entrada' => $data_entrada,
            ':data_saida' => $data_saida,
            ':valor' => $valor,
            ':tipo_pagamento' => $tipo_pagamento,
            ':status' => $status,
            ':id' => $id_ordem
        ]);

        // Redirecionar de volta para a lista
        header("Location: ordem_serv.php");
        exit();

    } catch (PDOException $e) {
        die("Erro ao atualizar ordem: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Ordem de Serviço</title>
<link rel="stylesheet" href="css/form.css">
</head>
<div id="modalPeca" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
  <div style="background:#333; margin:10% auto; padding:20px; width:400px; border-radius:5px;">
    <h3>Atribuir Peça</h3>
    <form id="formPeca" method="post" action="atribuir_peca.php">
      <input type="hidden" name="id_ordem_serv" id="id_ordem_serv">
      <label>Peça:</label>
      <select name="id_produto" required>
        <?php
          $produtos = $pdo->query("SELECT p.id_produto, p.nome_produto, e.quantidade 
                                   FROM produto p
                                   JOIN estoque e ON e.id_estoque = p.idestoque
                                   WHERE e.quantidade > 0
                                   ORDER BY p.nome_produto")->fetchAll();
          foreach ($produtos as $p) {
              echo "<option value='{$p['id_produto']}'>" . 
                   htmlspecialchars($p['nome_produto']) . " (Qtd: {$p['quantidade']})</option>";
          }
        ?>
      </select>
      <label>Quantidade:</label>
      <input type="number" name="quantidade" min="1" value="1" required>
      <div style="margin-top:10px;">
        <button type="submit">Salvar</button>
        <br><br>
        <button type="button" onclick="fecharModal()">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<script>
function abrirModalPeca(idOS) {
  document.getElementById('id_ordem_serv').value = idOS;
  document.getElementById('modalPeca').style.display = 'block';
}
function fecharModal() {
  document.getElementById('modalPeca').style.display = 'none';
}
</script>

<body>
<div class="form-container">
    <h2>✏️ Editar Ordem de Serviço</h2>
    <form method="post">
        <label>Aparelho:</label>
        <input type="text" name="Aparelho" value="<?php echo htmlspecialchars($ordem['Aparelho']); ?>" required>

        <label>Serviço:</label>
        <input type="text" name="servico" value="<?php echo htmlspecialchars($ordem['servico']); ?>" required>

        <label>Data de Entrada:</label>
        <input type="date" name="data_entrada" value="<?php echo htmlspecialchars($ordem['data_entrada']); ?>" required>

        <label>Data de Saída:</label>
        <input type="date" name="data_saida" value="<?php echo htmlspecialchars($ordem['data_saida']); ?>">

<!-- Estilizção da tabela de peças-->
<style>
.ordem-table {
  width: 80%;
  border-collapse: collapse;
  background-color: #1e1e1e;
  border-radius: 8px;
  overflow: hidden;
  margin: 0 auto;
}

.ordem-table th,
.ordem-table td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid #333;
}

.ordem-table th {
  background-color: #2c2c2c;
}
</style>
<!-- peças atruibuidas a ordem de servico-->
<h3>🔧 Peças Atribuídas</h3>
<table class="ordem-table">
  <thead>
    <tr>
      <th>Peça</th>
      <th>Quantidade</th>
      <th>Valor Unitário</th>
      <th>Total</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    <?php if (count($pecasOrdem)): ?>
      <?php foreach ($pecasOrdem as $p): ?>
        <tr>
          <td><?php echo htmlspecialchars($p['nome_produto']); ?></td>
          <td><?php echo (int)$p['quantidade']; ?></td>
          <td>R$ <?php echo number_format($p['valor'], 2, ',', '.'); ?></td>
          <td>R$ <?php echo number_format($p['valor'] * $p['quantidade'], 2, ',', '.'); ?></td>
          <td>
            <a href="remover_peca.php?id_ordem=<?php echo $id_ordem; ?>&id_produto=<?php echo $p['id_produto']; ?>" 
               onclick="return confirm('Tem certeza que deseja remover esta peça da ordem?');">🗑️</a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="5" style="text-align:center;">Nenhuma peça atribuída.</td></tr>
    <?php endif; ?>
  </tbody>
</table>
<br>
<button type="button" onclick="abrirModalPeca(<?php echo (int)$ordem['id_ordem_serv']; ?>)">
  ➕ Adicionar Peça
</button>
        <label>Valor:</label>
        <input type="number" step="0.01" name="valor" value="<?php echo htmlspecialchars($ordem['valor']); ?>">

        <label>Tipo de Pagamento:</label>
        <input type="text" name="tipo_pagamento" value="<?php echo htmlspecialchars($ordem['tipo_pagamento']); ?>">

        <label>Status:</label>
        <select name="status" required>
            <option value="Pendente" <?php if($ordem['status']=='Pendente') echo 'selected'; ?>>Pendente</option>
            <option value="Em andamento" <?php if($ordem['status']=='Em andamento') echo 'selected'; ?>>Em andamento</option>
            <option value="Concluído" <?php if($ordem['status']=='Concluído') echo 'selected'; ?>>Concluído</option>
            <option value="Cancelado" <?php if($ordem['status']=='Cancelado') echo 'selected'; ?>>Cancelado</option>
        </select>

        <div style="margin-top:1rem;">
            <button type="submit">Salvar Alterações</button>
            <br><br>
            <a href="ordem_serv.php"><button type="button">Cancelar</button></a>
        </div>
    </form>
</div>
<style>
    
    
</style>
</body>
</html>
