<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $nova_senha = $_POST['nova_senha'];
    $conf_senha = $_POST['conf_senha'];

    if ($nova_senha === $conf_senha) {
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        $sql = "UPDATE usuario SET senha = :senha, senha_temporaria = 0 WHERE id_usuario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':id', $_SESSION['usuario_id']);
        $stmt->execute();

        echo "<script>alert('Senha alterada com sucesso!');window.location.href='login.php';</script>";
        exit();
    } else {
        echo "<script>alert('As senhas não coincidem!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trocar Senha</title>
  <link rel="stylesheet" href="css/form.css" />
</head>
<body>
  <div class="form-container">
    <h2>🔄 Trocar Senha</h2>
    <form method="POST">
      <label for="nova_senha">Nova Senha</label>
      <input type="password" id="nova_senha" name="nova_senha" required>

      <label for="conf_senha">Confirmar Senha</label>
      <input type="password" id="conf_senha" name="conf_senha" required>

      <button type="submit" class="btn-login">Alterar Senha</button>
    </form>
  </div>
</body>
</html>
