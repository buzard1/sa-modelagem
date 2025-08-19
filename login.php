<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $sql = "SELECT * FROM usuario WHERE email = :email AND senha = :senha LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // LOGIN OK
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['cargo'] = $usuario['cargo'];
        $_SESSION['id_usuario'] = $usuario['id_usuario'];

        header("Location: cadastro-cliente.php");
        exit();
    } else {
        echo "<script>alert('E-mail ou senha inválidos!');window.location.href='login.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Manutenção de Celulares</title>
  <link rel="stylesheet" href="css/login.css" />
  <link rel="icon" href="img/logo.png" type="image/png">
</head>
<body>
  <div class="login-container">
    <div class="logo-login fade-in">
      <img src="img/logo.png" alt="Mobile Repair" class="logo-img" />
    </div>

    <h2 class="fade-in">Login</h2>
    <form action="login.php" method="POST">
      <div class="form-group fade-in delay-1">
        <label for="email">E-mail:</label>
        <input type="text" id="email" name="email" required />
      </div>
      <div class="form-group fade-in delay-2">
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required />
      </div>
      <button type="submit" class="btn-login fade-in delay-3">Entrar</button>
      <a href="cadastro.html" class="btn-registrar fade-in delay-4">Registrar</a>
    </form>
  </div>
</body>
</html>
