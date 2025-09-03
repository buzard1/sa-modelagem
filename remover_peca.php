<?php
require_once 'conexao.php';
session_start();

$id_ordem   = (int)($_GET['id_ordem'] ?? 0);
$id_produto = (int)($_GET['id_produto'] ?? 0);

if ($id_ordem <= 0 || $id_produto <= 0) {
    die("Parâmetros inválidos.");
}

try {
    $pdo->beginTransaction();

    // Buscar quantidade usada e id_estoque
    $sql = "SELECT sp.quantidade, e.id_estoque 
            FROM servico_produto sp
            JOIN produto p ON sp.id_produto = p.id_produto
            JOIN estoque e ON p.idestoque = e.id_estoque
            WHERE sp.id_ordem_serv = ? AND sp.id_produto = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_ordem, $id_produto]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        throw new Exception("Peça não encontrada nesta ordem.");
    }

    // Devolver ao estoque
    $sql = "UPDATE estoque SET quantidade = quantidade + ? WHERE id_estoque = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$item['quantidade'], $item['id_estoque']]);

    // Remover da OS
    $sql = "DELETE FROM servico_produto WHERE id_ordem_serv = ? AND id_produto = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_ordem, $id_produto]);

    $pdo->commit();
    header("Location: editar_ordem.php?id=" . $id_ordem);
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    die("Erro ao remover peça: " . $e->getMessage());
}
