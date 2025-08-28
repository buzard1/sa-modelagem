<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['cargo'])) { echo "Acesso negado"; exit(); }

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['id'])) {
  $id = (int)$_POST['id'];
  $stmt = $pdo->prepare("DELETE FROM ordem_serv WHERE id_ordem_serv = :id");
  $stmt->bindParam(':id',$id,PDO::PARAM_INT);
  if ($stmt->execute()) echo "sucesso"; else echo "erro";
} else {
  echo "requisicao invalida";
}
