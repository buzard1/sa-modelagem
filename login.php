<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD']== "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($usuario && password_verify($senha, $usuario['senha'])){
        // LOGIN BEM SUCEDIDO DEFINE AS VARIÁVEIS DE SESSÃO
        $_SESSION['usuario'] = $usuario['nome'];
        $_SESSION['perfil'] = $usuario['id_perfil'];
        $_SESSION['id_usuario'] = $usuario['id_usuario'];


        // VERIFICA SE A SENHA É TEMPORÁRIA
        if ($usuario['senha_temporaria']){
        // REDIRECIONA PARA A TROCA DE SENHA
        header("location: alterar_senha.php");
        exit();
} else {
        // REDIRECIONA PARA A PÁGINA PRINCIPAL
        header("location: principal.php");
        exit();
}
    }else{
        // LOGIN INVALIDO
        echo "<script>alert('E-mail ou senha inválidos!');window.location.href = 'login.php';
        </script>";
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
    <!-- Logo Centralizada -->
    <div class="logo-login fade-in">
      <img src="img/logo.png" alt="Mobile Repair" class="logo-img" />
    </div>
  
    <h2 class="fade-in">Login</h2>
    <form>
      <div class="form-group fade-in delay-1">
        <label for="usuario">Usuário:</label>
        <input type="text" id="usuario" name="usuario" required />
      </div>
      <div class="form-group fade-in delay-2">
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required />
      </div>
      <a href="cadastro-ordem_serv.html" class="btn-login fade-in delay-3">Entrar</a>
      <a href="cadastro.html" class="btn-registrar fade-in delay-4">Registrar</a>
    </form>
    <a href="recuperar-email.html" class="link-redefinir fade-in delay-4">Esqueceu a senha?</a>
  </div>
  </div>
</body>
</html>
