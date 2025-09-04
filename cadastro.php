<?php
require_once 'conexao.php'; // Conexão com banco

// PROCESSAMENTO DO FORMULÁRIO DE CADASTRO DE USUÁRIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome   = $_POST["nome"];
    $email  = $_POST["email"];
    $senha  = password_hash($_POST["senha"], PASSWORD_DEFAULT); // senha criptografada
    $cargo  = $_POST["cargo"];

    try {
        // Insere novo usuário
        $stmt = $pdo->prepare("INSERT INTO usuario (nome, email, senha, cargo) VALUES (:nome, :email, :senha, :cargo)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':cargo', $cargo);

        if ($stmt->execute()) {
            echo "<script>alert('✅ Usuário cadastrado com sucesso!'); window.location='usuarios.php';</script>";
        } else {
            echo "<script>alert('❌ Erro ao cadastrar usuário!');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Erro: " . $e->getMessage() . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Usuário</title>
  <link rel="stylesheet" href="css/form.css">
</head>
<body>
  <div class="form-container">
    <h2>👥 Cadastro de Usuário</h2>
    <form method="POST">
      <label for="nome">Nome:</label>
      <input type="text" id="nome" name="nome" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>

      <label for="senha">Senha:</label>
      <input type="password" id="senha" name="senha" required>

      <label for="cargo">Cargo:</label>
      <select id="cargo" name="cargo" required>
        <option value="Gerente">Gerente</option>
        <option value="Atendente">Atendente</option>
        <option value="Tecnico">Técnico</option>
      </select>

      <button type="submit">Cadastrar</button>
    </form>
  </div>
</body>
</html>
