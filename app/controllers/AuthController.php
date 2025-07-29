<?php
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND status = 'ativo'");
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && hash('sha256', $senha) === $usuario['senha']) {
        $_SESSION['usuario'] = $usuario;
        ob_clean(); // Limpa o buffer antes do redirect
        header("Location: /dashboard");
    } else {
        ob_clean(); // Limpa o buffer antes do redirect
        header("Location: /login?erro=1");
    }
    exit;
}
