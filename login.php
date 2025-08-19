<?php
session_start();
require_once 'conexao.php';

// Função para gerar senha hashada
function gerarSenhaHashada($senha) {
  return password_hash($senha, PASSWORD_DEFAULT);
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $email = $_POST['email'];
  $senha = $_POST['senha'];

  // Verificar se o e-mail e a senha estão corretos
  $sql = "SELECT * FROM usuario WHERE email = :email";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($usuario && password_verify($senha, $usuario['senha'])) {
    // Login bem-sucedido
    $_SESSION['email'] = $usuario['email'];
    $_SESSION['cargo'] = $usuario['cargo'];
    $_SESSION['id_usuario'] = $usuario['id_usuario'];

    header("location: ordem_serv.html");
    exit();
  } else {
    // Login inválido
    echo "<script>alert('E-mail ou senha inválidos!');window.location.href = 'login.php';</script>";
  }
}

// Cadastrar novo usuário
if (isset($_POST['cadastrar'])) {
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $senha = $_POST['senha'];
  $cargo = $_POST['cargo'];

  // Gerar senha hashada
  $senha_hashada = gerarSenhaHashada($senha);

  // Inserir novo usuário no banco de dados
  $sql = "INSERT INTO usuario (nome, email, senha, cargo) VALUES (:nome, :email, :senha, :cargo)";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':nome', $nome);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':senha', $senha_hashada);
  $stmt->bindParam(':cargo', $cargo);
  $stmt->execute();

  echo "<script>alert('Usuário cadastrado com sucesso!');window.location.href = 'login.php';</script>";
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
    <!-- Logo Centralizada -->
    <div class="logo-login fade-in">
      <img src="img/logo.png" alt="Mobile Repair" class="logo-img" />
    </div>
  
    <h2 class="fade-in">Login</h2>
    <form action="login.php" method="POST">
      <div class="form-group fade-in delay-1">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required />
      </div>
      <div class="form-group fade-in delay-2">
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required />
      </div>
      <button type="submit" class="btn-login fade-in delay-3">Entrar</button>
      <a href="cadastro.html" class="btn-registrar fade-in delay-4">Registrar</a>
    </form>
    <a href="recuperar-email.html" class="link-redefinir fade-in delay-4">Esqueceu a senha?</a>
  </div>
</body>
</html>