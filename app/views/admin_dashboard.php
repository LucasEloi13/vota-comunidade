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
    <style>
        body {
            background-color: #f6f6f9;
        }
        .navbar {
            background-color: #4339F2;
        }
        .navbar a, .navbar-brand {
            color: white !important;
        }
        .card-option {
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.2s ease;
        }
        .card-option:hover {
            transform: scale(1.02);
        }
        .card-option a {
            text-decoration: none;
            color: #4339F2;
            font-weight: 600;
        }
        .logout-btn {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar px-4 py-3">
        <span class="navbar-brand fw-bold">Vota Comunidade</span>
        <div>
            <a href="/dashboard" class="me-3">Início</a>
            <a href="/gerenciar-sindicos">Síndicos</a>
            <a href="#" class="ms-3">Moradores</a>
            <a href="#" class="ms-3">Condomínios</a>
            <a href="/logout" class="btn logout-btn ms-4">Sair</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="fw-bold mb-4">Dashboard do Administrador</h2>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="p-4 bg-white card-option">
                    <a href="/gerenciar-sindicos">Gerenciar Síndicos</a>
                    <p class="text-muted mt-2 mb-0">Aprovar/rejeitar cadastros, editar informações.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4 bg-white card-option">
                    <a href="#">Gerenciar Moradores</a>
                    <p class="text-muted mt-2 mb-0">Aprovar/rejeitar cadastros, editar informações de moradores.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4 bg-white card-option">
                    <a href="#">Gerenciar Condomínios</a>
                    <p class="text-muted mt-2 mb-0">Cadastrar e editar informações de condomínios.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4 bg-white card-option">
                    <a href="#">Visualizar Resultados de Votações</a>
                    <p class="text-muted mt-2 mb-0">Acompanhar resultados de todas as votações.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
