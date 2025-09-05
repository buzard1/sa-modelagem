<?php
session_start();
require_once 'conexao.php';

// Função para gerar senha temporária
function gerarSenhaTemporaria($tamanho = 8) {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    return substr(str_shuffle($caracteres), 0, $tamanho);
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = $_POST['email'] ?? '';

    // Verifica se o e-mail existe
    $sql = "SELECT * FROM usuario WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Gera senha temporária
        $senhaTemp = gerarSenhaTemporaria();
        $senhaHash = password_hash($senhaTemp, PASSWORD_DEFAULT);

        // Atualiza no banco com senha temporária
        $sql = "UPDATE usuario SET senha = :senha, senha_temporaria = 1 WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $senhaHash);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Simula envio do e-mail (gera um arquivo .txt)
        $arquivo = "senha_temp_" . $usuario['id_usuario'] . ".txt";
        $conteudo = "Olá, " . $usuario['email'] . "!\n\n";
        $conteudo .= "Sua senha temporária é: " . $senhaTemp . "\n";
        $conteudo .= "Use essa senha para acessar o sistema. Após o login, você será redirecionado para alterar sua senha.\n\n";
        $conteudo .= "Atenciosamente,\nEquipe de Suporte";
        file_put_contents($arquivo, $conteudo);

        echo "<script>alert('Uma senha temporária foi enviada (simulação em arquivo .txt). Verifique o arquivo: {$arquivo}');window.location.href='index.php';</script>";
        exit();
    } else {
        echo "<script>alert('E-mail não encontrado!');window.location.href='redefinir-senha.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redefinir Senha</title>
  <link rel="stylesheet" href="css/form.css" />
</head>
<body>
  <div class="form-container">
    <h2>🔑 Redefinir Senha</h2>
    <form method="POST">
      <label for="email">Digite seu e-mail cadastrado</label>
      <input type="email" id="email" name="email" required>
      <button type="submit" class="btn-login">Enviar senha temporária</button>
    </form>
  </div>
</body>
</html>
