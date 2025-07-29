<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Vota Comunidade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="min-width: 300px;">
        <h3 class="mb-3">Login</h3>
        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger">Credenciais inválidas</div>
        <?php endif; ?>
        <form method="POST" action="/auth">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
