<?php
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header("Location: /login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Editar Síndico - Vota Comunidade</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f6f6f9;
    }
    .navbar {
      background-color: #4339F2;
    }
    .navbar a,
    .navbar-brand {
      color: white !important;
    }
    .logout-btn {
      background-color: red;
      color: white !important;
    }
    .form-container {
      background: white;
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
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
    <div class="d-flex align-items-center mb-4">
      <a href="/gerenciar-sindicos" class="btn btn-outline-secondary me-3">
        <i class="fas fa-arrow-left"></i> Voltar
      </a>
      <h3 class="fw-bold mb-0">Editar Síndico</h3>
    </div>

    <!-- Mensagens de feedback -->
    <?php if (isset($_SESSION['msg_error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['msg_error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php unset($_SESSION['msg_error']); ?>
    <?php endif; ?>

    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="form-container">
          <form method="POST">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="nome" class="form-label">Nome Completo *</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($sindico['nome']) ?>" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="email" class="form-label">E-mail *</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($sindico['email']) ?>" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" value="<?= htmlspecialchars($sindico['cpf'] ?? '') ?>" placeholder="000.000.000-00">
              </div>
              <div class="col-md-6 mb-3">
                <label for="senha" class="form-label">Nova Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Deixe em branco para manter a senha atual">
                <div class="form-text">Mínimo 6 caracteres</div>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label">Status Atual:</label>
              <span class="badge bg-<?= $sindico['status'] === 'ativo' ? 'success' : ($sindico['status'] === 'pendente' ? 'warning' : 'danger') ?> ms-2">
                <?= ucfirst($sindico['status']) ?>
              </span>
              <div class="form-text">Para alterar o status, use os botões na listagem principal.</div>
            </div>

            <div class="d-flex justify-content-end gap-2">
              <a href="/gerenciar-sindicos" class="btn btn-secondary">Cancelar</a>
              <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  
  <script>
    // Máscara para CPF
    document.getElementById('cpf').addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');
      value = value.replace(/(\d{3})(\d)/, '$1.$2');
      value = value.replace(/(\d{3})(\d)/, '$1.$2');
      value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
      e.target.value = value;
    });
  </script>
</body>
</html>
