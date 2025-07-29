<?php
if (!isset($_SESSION['usuario'])) {
    header("Location: /login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Vota Comunidade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-white">
    <nav class="navbar navbar-light bg-light px-4">
        <span class="navbar-brand">Vota Comunidade</span>
        <a href="/logout" class="btn btn-outline-danger">Sair</a>
    </nav>

    <div class="container mt-4">
        <h2>Bem-vindo, <?= $_SESSION['usuario']['nome'] ?>!</h2>
        <p class="text-muted">Tipo de usuário: <?= $_SESSION['usuario']['tipo'] ?></p>
    </div>
</body>
</html>
