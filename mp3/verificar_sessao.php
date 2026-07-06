<?php
header('Content-Type: application/json');
session_start();

if (isset($_SESSION['utilizador_id'])) {
    echo json_encode([
        'logado' => true,
        'email' => $_SESSION['utilizador_email'],
        'handle' => '@' . $_SESSION['utilizador_nome'],
        'nome' => $_SESSION['utilizador_nome']
    ]);
} else {
    echo json_encode(['logado' => false]);
}
