<?php
session_start();
require_once 'conexao.php';

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pega os dados do formulário
    $nome = $_POST["nome_fornecedor"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];

    // Valida os dados do formulário
    if (empty($nome) || empty($telefone) || empty($email)) {
        echo "Por favor, preencha todos os campos.";
    } else {
        try {
            // Conexão com o banco de dados
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepara a consulta SQL
            $sql = "INSERT INTO fornecedor (nome_fornecedor, telefone, email) VALUES (:nome, :telefone, :email)";
            $stmt = $pdo->prepare($sql);

            // Insere os dados no banco de dados
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            echo "Fornecedor cadastrado com sucesso!";
        } catch (PDOException $e) {
            echo "Erro ao cadastrar fornecedor: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cadastro de Fornecedor</title>
  <link rel="stylesheet" href="css/sidebar.css" />
  <link rel="stylesheet" href="css/fornecedor.css" />
  <link rel="icon" href="img/logo.png" type="image/png">
</head>
<body>
  <!-- Sidebar fixa -->
  <nav class="sidebar">
    <div class="logo">
      <img src="img/logo.png" alt="Logo do sistema">
    </div>
    <ul class="menu">
      <li><a href="dashboard.html"> <span>Perfil</span></a></li>
      <li><a href="cadastro-cliente.html"> <span>Cadastro Cliente</span></a></li>
      <li><a href="cadastro-ordem_serv.html"> <span>Cadastro de <br>Ordem de Serviço</span></a></li>
      <li><a href="ordem_serv.html"> <span>Ordem de serviço</span></a></li>
      <li><a href="relatorio.html"> <span>Relatórios</span></a></li>
      <li><a href="estoque.html"> <span>Estoque</span></a></li>
      <li><a href="usuarios.html"> <span>Usuários</span></a></li>
      <li><a href="cadastro_fornecedor.php"> <span>Fornecedores</span></a></li>
      <li><a href="suporte.html"> <span>Suporte</span></a></li>
      <li><a href="login.html"> <span>Sair</span></a></li>
    </ul>
  </nav>
  <div class="form-container">
    <h2> Cadastro de Fornecedor</h2>
    <form action="cadastro_fornecedor.php" method="POST">
      <div class="form-group">
        <label for="nome_fornecedor">Nome da Empresa:</label>
        <input type="text" id="nome_fornecedor" name="nome_fornecedor" required />
      </div>
      <div class="form-group">
        <label for="telefone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone" required />
      </div>
      <div class="form-group">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required />
      </div>
      

      <button type="submit" class="btn-submit">Cadastrar</button>
    </form>

   
  </div>
  <!-- Script: ativa o menu da página atual -->
  <script>
    const links = document.querySelectorAll('.sidebar .menu li a');
    const currentPage = window.location.pathname.split('/').pop();

    links.forEach(link => {
      if (link.getAttribute('href') === currentPage) {
        link.classList.add('active');
      }
    });
    
  </script>
  <!-- Máscaras de entrada -->
  <script src="[https://cdn.jsdelivr.net/npm/inputmask/dist/inputmask.min.js"></script>
  <script>
    Inputmask({ mask: "(99) 99999-9999" }).mask("#telefone");
    Inputmask({ mask: "999.999.999-99" }).mask("#cpf");
  </script>
</body>
</html>
