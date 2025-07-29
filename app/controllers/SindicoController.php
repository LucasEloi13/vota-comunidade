<?php
require_once __DIR__ . '/../models/Admin.php';

class SindicoController {
    private $adminModel;
    
    public function __construct() {
        $this->adminModel = new Admin();
    }
    
    // Listar síndicos
    public function listar() {
        $busca = $_GET['busca'] ?? '';
        $sindicos = $this->adminModel->getSindicos($busca);
        
        include __DIR__ . '/../views/gerenciar_sindicos.php';
    }
    
    // Aprovar síndico
    public function aprovar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            
            if ($id && $this->adminModel->atualizarStatusSindico($id, 'ativo')) {
                $_SESSION['msg_success'] = 'Síndico aprovado com sucesso!';
            } else {
                $_SESSION['msg_error'] = 'Erro ao aprovar síndico.';
            }
        }
        
        header('Location: /gerenciar-sindicos');
        exit;
    }
    
    // Rejeitar síndico
    public function rejeitar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            
            if ($id && $this->adminModel->atualizarStatusSindico($id, 'rejeitado')) {
                $_SESSION['msg_success'] = 'Síndico rejeitado.';
            } else {
                $_SESSION['msg_error'] = 'Erro ao rejeitar síndico.';
            }
        }
        
        header('Location: /gerenciar-sindicos');
        exit;
    }
    
    // Editar síndico
    public function editar() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /gerenciar-sindicos');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = [
                'nome' => $_POST['nome'] ?? '',
                'email' => $_POST['email'] ?? '',
                'cpf' => $_POST['cpf'] ?? '',
                'senha' => $_POST['senha'] ?? ''
            ];
            
            // Validar se email já existe
            if ($this->adminModel->emailExiste($dados['email'], $id)) {
                $_SESSION['msg_error'] = 'Este email já está sendo usado por outro usuário.';
            } else {
                if ($this->adminModel->atualizarSindico($id, $dados)) {
                    $_SESSION['msg_success'] = 'Síndico atualizado com sucesso!';
                    header('Location: /gerenciar-sindicos');
                    exit;
                } else {
                    $_SESSION['msg_error'] = 'Erro ao atualizar síndico.';
                }
            }
        }
        
        $sindico = $this->adminModel->getSindicoById($id);
        if (!$sindico) {
            header('Location: /gerenciar-sindicos');
            exit;
        }
        
        include __DIR__ . '/../views/editar_sindico.php';
    }
    
    // Criar novo síndico
    public function criar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = [
                'nome' => $_POST['nome'] ?? '',
                'email' => $_POST['email'] ?? '',
                'cpf' => $_POST['cpf'] ?? '',
                'senha' => $_POST['senha'] ?? '',
                'status' => $_POST['status'] ?? 'pendente'
            ];
            
            // Validar se email já existe
            if ($this->adminModel->emailExiste($dados['email'])) {
                $_SESSION['msg_error'] = 'Este email já está sendo usado.';
            } else {
                if ($this->adminModel->criarSindico($dados)) {
                    $_SESSION['msg_success'] = 'Síndico criado com sucesso!';
                    header('Location: /gerenciar-sindicos');
                    exit;
                } else {
                    $_SESSION['msg_error'] = 'Erro ao criar síndico.';
                }
            }
        }
        
        include __DIR__ . '/../views/criar_sindico.php';
    }
}

// Processar ação
$controller = new SindicoController();
$acao = $_GET['acao'] ?? 'listar';

switch ($acao) {
    case 'aprovar':
        $controller->aprovar();
        break;
    case 'rejeitar':
        $controller->rejeitar();
        break;
    case 'editar':
        $controller->editar();
        break;
    case 'criar':
        $controller->criar();
        break;
    default:
        $controller->listar();
        break;
}
?>
