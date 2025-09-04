<?php
require_once 'conexao.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_ordem_serv = (int)$_POST['id_ordem_serv'];
    $id_produto    = (int)$_POST['id_produto'];
    $qtd           = (int)$_POST['quantidade'];
    if (!isset($_SESSION['cargo']) || !in_array($_SESSION['cargo'], ["Gerente","Tecnico"])) {
        header("Location: dashboard.php");
        exit();
    }

    try {
        $pdo->beginTransaction();

        // Pegar estoque atual
        $sql = "SELECT e.id_estoque, e.quantidade 
                FROM produto p 
                JOIN estoque e ON e.id_estoque = p.idestoque 
                WHERE p.id_produto = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_produto]);
        $estoque = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$estoque || $estoque['quantidade'] < $qtd) {
            throw new Exception("Estoque insuficiente!");
        }

        // Inserir na tabela de relação
        $sql = "INSERT INTO servico_produto (quantidade, id_produto, id_ordem_serv) 
                VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$qtd, $id_produto, $id_ordem_serv]);

        // Atualizar estoque
        $sql = "UPDATE estoque SET quantidade = quantidade - ? WHERE id_estoque = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$qtd, $estoque['id_estoque']]);

        $pdo->commit();
        header("Location: editar_ordem.php?id=" . $id_ordem_serv);
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erro: " . $e->getMessage());
    }
}