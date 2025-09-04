<?php
session_start();
require_once 'conexao.php';

// Apenas Gerente e Técnico podem acessar
if (!isset($_SESSION['cargo']) || ($_SESSION['cargo'] != "Gerente" && $_SESSION['cargo'] != "Tecnico")) {
    echo "Acesso Negado!";
    header("Location: dashboard.php");
    exit();
}

// Pega id da ordem da URL (GET)
$id_ordem = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Se formulário enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_peca  = $_POST["id_peca"];
    $quantidade = $_POST["quantidade"];

    try {
        // Insere vínculo entre peça e ordem
        $stmt = $pdo->prepare("INSERT INTO ordem_peca (id_ordem, id_peca, quantidade) VALUES (:id_ordem, :id_peca, :quantidade)");
        $stmt->bindParam(':id_ordem', $id_ordem);
        $stmt->bindParam(':id_peca', $id_peca);
        $stmt->bindParam(':quantidade', $quantidade);

        if ($stmt->execute()) {
            echo "<script>alert('✅ Peça atribuída com sucesso!'); window.location='ordem_serv.php';</script>";
        } else {
            echo "<script>alert('❌ Erro ao atribuir peça!');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Erro: " . $e->getMessage() . "');</script>";
    }
}

// Buscar peças do estoque
$pecas = $pdo->query("SELECT idpeca, nome, quantidade FROM peca")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Atribuir Peça</title>
  <link rel="stylesheet" href="css/form.css">
</head>
<body>
  <div class="form-container">
    <h2>🔧 Atribuir Peça à Ordem</h2>
    <form method="POST">
      <label for="id_peca">Peça:</label>
      <select name="id_peca" id="id_peca" required>
        <?php foreach ($pecas as $peca): ?>
          <option value="<?= $peca['idpeca'] ?>">
            <?= $peca['nome'] ?> (<?= $peca['quantidade'] ?> disponíveis)
          </option>
        <?php endforeach; ?>
      </select>

      <label for="quantidade">Quantidade:</label>
      <input type="number" id="quantidade" name="quantidade" min="1" required>

      <button type="submit">Atribuir</button>
    </form>
  </div>
</body>
</html>
