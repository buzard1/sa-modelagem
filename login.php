<?php 
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Busca usuário pelo e-mail
    $sql = "SELECT * FROM usuario WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $hashNoBanco = $usuario['senha'];
        $autenticado = false;

        // 1) Tenta verificar com hash (senhas novas / temporárias)
        if (password_verify($senha, $hashNoBanco)) {
            $autenticado = true;

            // Se necessário, rehash
            if (password_needs_rehash($hashNoBanco, PASSWORD_DEFAULT)) {
                $novoHash = password_hash($senha, PASSWORD_DEFAULT);
                $up = $pdo->prepare("UPDATE usuario SET senha = :senha WHERE id_usuario = :id");
                $up->bindParam(':senha', $novoHash);
                $up->bindParam(':id', $usuario['id_usuario']);
                $up->execute();
            }
        } 
        // 2) Compatibilidade com senhas antigas salvas em texto puro
        elseif (hash_equals($hashNoBanco, $senha)) {
            $autenticado = true;

            // Migra senha antiga para hash
            $novoHash = password_hash($senha, PASSWORD_DEFAULT);
            $up = $pdo->prepare("UPDATE usuario SET senha = :senha WHERE id_usuario = :id");
            $up->bindParam(':senha', $novoHash);
            $up->bindParam(':id', $usuario['id_usuario']);
            $up->execute();
        }

        if ($autenticado) {
            // Salva sessão
            $_SESSION['nome'] = $usuario['nome_completo']; // ✅ corrige aqui
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['cargo'] = $usuario['cargo'] ?? null;

            // 🔑 Se senha for temporária → forçar troca
            if (!empty($usuario['senha_temporaria']) && $usuario['senha_temporaria'] == 1) {
                header("Location: trocar-senha.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        }
    }

    // Falha no login
    echo "<script>alert('E-mail ou senha inválidos!');window.location.href='login.php';</script>";
    exit();
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
<<<<<<< Updated upstream
      
      <!-- Botão de redefinir senha -->
      <a href="redefinir-senha.php" class="btn-registrar fade-in delay-5">Esqueci minha senha</a>
=======
      <br><br>
      <a href="redefinir-senha.php" class="link-redefinir fade-in delay-4">Esqueci minha senha</a>
     
   
>>>>>>> Stashed changes
    </form>
  </div>
</body>
</html>
