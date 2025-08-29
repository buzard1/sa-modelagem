<?php
// usuarios_ajax.php
require_once 'conexao.php';
header('Content-Type: application/json; charset=utf-8');

try {
    // Handle GET request (fetch data)
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $filtroCargo = isset($_GET['filtro_cargo']) ? $_GET['filtro_cargo'] : '';

        if ($filtroCargo === 'Cliente') {
            $sql = "SELECT cpf, nome AS nome_completo, email, 'Cliente' AS cargo, 1 AS ativo, telefone, endereco 
                    FROM cliente ORDER BY cpf DESC";
            $stmt = $pdo->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            if (!empty($filtroCargo)) {
                $sql = "SELECT id_usuario, nome_completo, email, cargo, ativo 
                        FROM usuario WHERE cargo = :cargo ORDER BY id_usuario DESC";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':cargo' => $filtroCargo]);
            } else {
                $sql = "SELECT id_usuario, nome_completo, email, cargo, ativo 
                        FROM usuario ORDER BY id_usuario DESC";
                $stmt = $pdo->query($sql);
            }
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode(['success' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
    }
    // Handle POST requests (edit and delete)
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $acao = $input['acao'] ?? '';

        if ($acao === 'editar') {
    $id = $input['id'] ?? '';
    $nome = $input['nome'] ?? '';
    $email = $input['email'] ?? '';
    $cargo = $input['cargo'] ?? '';
    $ativo = isset($input['ativo']) ? 1 : 0;
    $senha = $input['senha'] ?? '';

    // ✅ validação apenas de campos obrigatórios
    if ($id === '' || $nome === '' || $email === '' || $cargo === '') {
        throw new Exception('Campos obrigatórios não preenchidos.');
    }

    if ($cargo === 'Cliente') {
        $cpf = $input['cpf'] ?? '';
        $telefone = $input['telefone'] ?? '';
        $endereco = $input['endereco'] ?? '';
    
        $sql = "UPDATE cliente 
                SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco, cpf = :novoCpf
                WHERE cpf = :cpf";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':telefone' => $telefone,
            ':endereco' => $endereco,
            ':novoCpf' => $cpf,
            ':cpf' => $id
        ]);
    } else {
        $sql = "UPDATE usuario 
                SET nome_completo = :nome, email = :email, cargo = :cargo, ativo = :ativo";
        $params = [
            ':nome' => $nome,
            ':email' => $email,
            ':cargo' => $cargo,
            ':ativo' => $ativo,
            ':id' => $id
        ];

        if (!empty($senha)) {
            $sql .= ", senha = :senha";
            $params[':senha'] = password_hash($senha, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id_usuario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
}elseif ($acao === 'excluir') {
    $id = $input['id'] ?? null;
    $cargo = $input['cargo'] ?? null;

    // 🚨 SE id não for número válido e cargo não for definido, não executa NADA
    if (empty($id) || empty($cargo)) {
        throw new Exception('ID ou cargo não fornecido.');
    }

    if ($cargo === 'Cliente') {
        $sql = "DELETE FROM cliente WHERE cpf = :id";
    } else {
        $sql = "DELETE FROM usuario WHERE id_usuario = :id";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

            echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        } else {
            throw new Exception('Ação inválida.');
        }
    } else {
        throw new Exception('Método HTTP não suportado.');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}