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
$handle = trim(strtolower(str_replace(' ', '_', $_POST['handle_utilizador'] ?? '')));

if (!$email || !$senha || !$handle) {
    echo json_encode(['erro' => 'Preenche todos os campos.']);
    exit;
}
if (!preg_match('/^[a-z0-9_]+$/', $handle)) {
    echo json_encode(['erro' => 'O @ só pode ter letras, números e underscores.']);
    exit;
}

try {
    // check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM utilizador WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        echo json_encode(['erro' => 'Este email já está registado.']);
        exit;
    }

    // check if handle already exists
    $stmt = $pdo->prepare("SELECT id FROM utilizador WHERE username = :handle");
    $stmt->execute([':handle' => $handle]);
    if ($stmt->fetch()) {
        echo json_encode(['erro' => 'Este @ já está em uso.']);
        exit;
    }

    $hashed = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO utilizador (username, email, senha_hash) VALUES (:username, :email, :senha_hash)");
    $stmt->execute([
        ':username' => $handle,
        ':email' => $email,
        ':senha_hash' => $hashed
    ]);

    $userId = $pdo->lastInsertId();

    $_SESSION['utilizador_id'] = $userId;
    $_SESSION['utilizador_nome'] = $handle;
    $_SESSION['utilizador_email'] = $email;

    echo json_encode(['sucesso' => true, 'email' => $email, 'handle' => '@' . $handle]);

} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro no sistema: ' . $e->getMessage()]);
}
