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
  <title>Gerenciar Síndicos - Vota Comunidade</title>
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
    .status-ativo {
      background-color: #c0f2cb;
      color: #12733c;
      padding: 2px 10px;
      border-radius: 20px;
      font-weight: 600;
    }
    .status-pendente {
      background-color: #f9f1c0;
      color: #927c00;
      padding: 2px 10px;
      border-radius: 20px;
      font-weight: 600;
    }
    .status-rejeitado {
      background-color: #f5c6cb;
      color: #842029;
      padding: 2px 10px;
      border-radius: 20px;
      font-weight: 600;
    }
    .tabela-container {
      background: white;
      border-radius: 16px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .navbar a.active {
      font-weight: bold;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <nav class="navbar px-4 py-3">
    <span class="navbar-brand fw-bold">Vota Comunidade</span>
    <div>
      <a href="/dashboard" class="me-3">Início</a>
      <a href="/gerenciar-sindicos" class="active">Síndicos</a>
      <a href="#" class="ms-3">Moradores</a>
      <a href="#" class="ms-3">Condomínios</a>
      <a href="/logout" class="btn logout-btn ms-4">Sair</a>
    </div>
  </nav>

  <div class="container mt-5">
    <h3 class="fw-bold mb-4">Gerenciar Síndicos</h3>

    <!-- Mensagens de feedback -->
    <?php if (isset($_SESSION['msg_success'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['msg_success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php unset($_SESSION['msg_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['msg_error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['msg_error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php unset($_SESSION['msg_error']); ?>
    <?php endif; ?>

    <div class="tabela-container">
      <div class="d-flex justify-content-between mb-3">
        <form method="GET" class="d-flex w-50">
          <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou email..." value="<?= htmlspecialchars($busca ?? '') ?>">
          <button type="submit" class="btn btn-outline-primary ms-2">Buscar</button>
        </form>
        <a href="/gerenciar-sindicos?acao=criar" class="btn btn-primary">Adicionar Síndico</a>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>NOME</th>
              <th>E-MAIL</th>
              <th>CPF</th>
              <th>STATUS</th>
              <th>DATA CADASTRO</th>
              <th>AÇÕES</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($sindicos)): ?>
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  <?= !empty($busca) ? 'Nenhum síndico encontrado com os critérios de busca.' : 'Nenhum síndico cadastrado.' ?>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($sindicos as $sindico): ?>
                <tr>
                  <td><?= htmlspecialchars($sindico['nome']) ?></td>
                  <td><?= htmlspecialchars($sindico['email']) ?></td>
                  <td><?= htmlspecialchars($sindico['cpf'] ?? 'Não informado') ?></td>
                  <td>
                    <span class="status-<?= $sindico['status'] ?>">
                      <?= ucfirst($sindico['status']) ?>
                    </span>
                  </td>
                  <td><?= date('d/m/Y', strtotime($sindico['criado_em'])) ?></td>
                  <td>
                    <a href="/gerenciar-sindicos?acao=editar&id=<?= $sindico['id'] ?>" class="text-primary me-2" title="Editar">
                      <i class="fas fa-edit"></i> Editar
                    </a>
                    
                    <?php if ($sindico['status'] === 'pendente'): ?>
                      <form method="POST" action="/gerenciar-sindicos?acao=aprovar" class="d-inline">
                        <input type="hidden" name="id" value="<?= $sindico['id'] ?>">
                        <button type="submit" class="btn btn-link text-success p-0 me-2" title="Aprovar" onclick="return confirm('Deseja aprovar este síndico?')">
                          <i class="fas fa-check"></i> Aprovar
                        </button>
                      </form>
                      
                      <form method="POST" action="/gerenciar-sindicos?acao=rejeitar" class="d-inline">
                        <input type="hidden" name="id" value="<?= $sindico['id'] ?>">
                        <button type="submit" class="btn btn-link text-danger p-0" title="Rejeitar" onclick="return confirm('Deseja rejeitar este síndico?')">
                          <i class="fas fa-times"></i> Rejeitar
                        </button>
                      </form>
                    <?php elseif ($sindico['status'] === 'rejeitado'): ?>
                      <form method="POST" action="/gerenciar-sindicos?acao=aprovar" class="d-inline">
                        <input type="hidden" name="id" value="<?= $sindico['id'] ?>">
                        <button type="submit" class="btn btn-link text-success p-0" title="Reativar" onclick="return confirm('Deseja reativar este síndico?')">
                          <i class="fas fa-undo"></i> Reativar
                        </button>
                      </form>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
