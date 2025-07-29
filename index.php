<?php
session_start();
ob_start(); // Inicia o buffer de saída

// Função para roteamento simples
function route($path) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    switch ($uri) {
        case '/':
        case '/login':
            include __DIR__ . '/app/views/login.php';
            break;
            
        case '/auth':
            include __DIR__ . '/app/controllers/AuthController.php';
            break;
            
        case '/dashboard':
            // Verificar se está logado
            if (!isset($_SESSION['usuario'])) {
                header("Location: /login");
                exit;
            }
            
            // Redirecionar baseado no tipo de usuário
            if ($_SESSION['usuario']['tipo'] === 'admin') {
                include __DIR__ . '/app/views/admin_dashboard.php';
            } else {
                include __DIR__ . '/app/views/dashboard.php';
            }
            break;
            
        case '/logout':
            ob_clean(); // Limpa o buffer antes do redirect
            session_destroy();
            header("Location: /login");
            exit;
            break;
            
        default:
            http_response_code(404);
            echo "<h1>404 - Página não encontrada</h1>";
            break;
    }
}

// Executar o roteamento
route($_SERVER['REQUEST_URI']);
ob_end_flush(); // Envia o buffer de saída
?>
