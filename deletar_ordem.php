<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário tem permissão
if (!isset($_SESSION['cargo']) || !in_array($_SESSION['cargo'], ["Gerente","Atendente"])) {
    header("Location: ordem_serv.php?erro=permissao");
    exit();
}

// Verifica se recebeu o ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ordem_serv.php?erro=id_invalido");
    exit();
}

$id = (int) $_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM ordem_serv WHERE id_ordem_serv = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: ordem_serv.php?sucesso=excluido");
    exit();
} catch (PDOException $e) {
    die("Erro ao excluir ordem: " . $e->getMessage());
}
