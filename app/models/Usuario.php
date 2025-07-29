<?php
require_once __DIR__ . '/../../config/database.php';

class Usuario {
    private $pdo;
    
    public function __construct() {
        // Incluir novamente o arquivo de configuração para garantir que $pdo existe
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }
    
    // Buscar usuário por email para login
    public function buscarPorEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND status = 'ativo'");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Buscar usuário por ID
    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Criar novo usuário (registro público)
    public function criar($dados) {
        $sql = "INSERT INTO usuarios (nome, email, senha, cpf, tipo, status) VALUES (:nome, :email, :senha, :cpf, :tipo, :status)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'senha' => hash('sha256', $dados['senha']),
            'cpf' => $dados['cpf'] ?? null,
            'tipo' => $dados['tipo'] ?? 'morador',
            'status' => $dados['status'] ?? 'pendente'
        ]);
    }
    
    // Atualizar perfil do usuário
    public function atualizarPerfil($id, $dados) {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email";
        $params = [
            'id' => $id,
            'nome' => $dados['nome'],
            'email' => $dados['email']
        ];
        
        // Se foi fornecida uma nova senha
        if (!empty($dados['senha'])) {
            $sql .= ", senha = :senha";
            $params['senha'] = hash('sha256', $dados['senha']);
        }
        
        // Se foi fornecido CPF
        if (isset($dados['cpf'])) {
            $sql .= ", cpf = :cpf";
            $params['cpf'] = $dados['cpf'];
        }
        
        $sql .= " WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    
    // Verificar se email já existe
    public function emailExiste($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $params = ['email' => $email];
        
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    
    // Verificar credenciais para login
    public function verificarCredenciais($email, $senha) {
        $usuario = $this->buscarPorEmail($email);
        
        if ($usuario && hash('sha256', $senha) === $usuario['senha']) {
            return $usuario;
        }
        
        return false;
    }
}
?>
