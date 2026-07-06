<?php
header('Content-Type: application/json');
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['erro' => 'Método não permitido']);
    exit;
}

$email = trim(strtolower($_POST['email_utilizador'] ?? ''));
$senha = $_POST['senha_utilizadora'] ?? '';

if (!$email || !$senha) {
    echo json_encode(['erro' => 'Preenche o email e a password.']);
    exit;
}

try {
    $sql = "SELECT * FROM utilizador WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilizador) {
        if (password_verify($senha, $utilizador['senha_hash'])) {
            $_SESSION['utilizador_id'] = $utilizador['id'];
            $_SESSION['utilizador_nome'] = $utilizador['username'];
            $_SESSION['utilizador_email'] = $utilizador['email'];

            echo json_encode([
                'sucesso' => true,
                'email' => $utilizador['email'],
                'handle' => '@' . $utilizador['username']
            ]);
        } else {
            echo json_encode(['erro' => 'Senha incorreta!']);
        }
    } else {
        echo json_encode(['erro' => 'Nenhum utilizador encontrado com esse email!']);
    }
} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro no sistema: ' . $e->getMessage()]);
}
