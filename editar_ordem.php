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
            <a href="ordem_serv.php"><button type="button">Cancelar</button></a>
        </div>
    </form>
</div>
</body>
</html>
