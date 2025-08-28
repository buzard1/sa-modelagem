<?php
// Conexão com o banco
$dsn = "mysql:host=localhost;dbname=sa_mobilerepair;charset=utf8";
$user = "root";
$pass = "";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}

// Função para detectar a tabela de clientes
function detectaTabelaCliente($pdo) {
    $possiveis = ['usuario', 'usuarios', 'clientes', 'cliente'];
    foreach ($possiveis as $t) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$t'");
        if ($stmt->rowCount() > 0) {
            return $t;
        }
    }
    return null;
}

$tabelaCliente = detectaTabelaCliente($pdo);

// Monta SQL
$sql = "SELECT o.id, o.cpf, o.aparelho, o.servico, o.valor, o.data";

if ($tabelaCliente) {
    $sql .= ", c.nome AS nome_cliente, c.telefone AS telefone_cliente, c.email AS email_cliente";
} else {
    $sql .= ", '' AS nome_cliente, '' AS telefone_cliente, '' AS email_cliente";
}

$sql .= " FROM ordem_serv o";

if ($tabelaCliente) {
    $sql .= " LEFT JOIN $tabelaCliente c ON c.cpf = o.cpf";
}

$stmt = $pdo->query($sql);
$ordens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Ordens de Serviço</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #007BFF;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
    </style>
</head>
<body>

<h2>Lista de Ordens de Serviço</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>CPF</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Aparelho</th>
            <th>Serviço</th>
            <th>Valor</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($ordens): ?>
            <?php foreach ($ordens as $o): ?>
                <tr>
                    <td><?php echo htmlspecialchars($o['id']); ?></td>
                    <td><?php echo htmlspecialchars($o['nome_cliente']); ?></td>
                    <td><?php echo htmlspecialchars($o['cpf']); ?></td>
                    <td><?php echo htmlspecialchars($o['telefone_cliente'] ?: $o['telefone']); ?></td>
                    <td><?php echo htmlspecialchars($o['email_cliente'] ?: $o['email']); ?></td>
                    <td><?php echo htmlspecialchars($o['aparelho']); ?></td>
                    <td><?php echo htmlspecialchars($o['servico']); ?></td>
                    <td>R$ <?php echo number_format($o['valor'], 2, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($o['data']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">Nenhuma ordem de serviço encontrada.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
