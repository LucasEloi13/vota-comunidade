<?php
require_once __DIR__ . '/../../config/database.php';

class Admin {
    private $pdo;
    
    public function __construct() {
        // Incluir novamente o arquivo de configuração para garantir que $pdo existe
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }
    
    // ==================== GERENCIAMENTO DE SÍNDICOS ====================
    
    // Buscar todos os síndicos
    public function getSindicos($busca = '') {
        $sql = "SELECT * FROM usuarios WHERE tipo = 'sindico'";
        $params = [];
        
        if (!empty($busca)) {
            $sql .= " AND (nome LIKE :busca OR email LIKE :busca)";
            $params['busca'] = "%{$busca}%";
        }
        
        $sql .= " ORDER BY nome ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Buscar síndico por ID
    public function getSindicoById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = :id AND tipo = 'sindico'");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Atualizar status do síndico
    public function atualizarStatusSindico($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET status = :status WHERE id = :id AND tipo = 'sindico'");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }
    
    // Criar novo síndico
    public function criarSindico($dados) {
        $sql = "INSERT INTO usuarios (nome, email, senha, cpf, tipo, status) VALUES (:nome, :email, :senha, :cpf, 'sindico', :status)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'senha' => hash('sha256', $dados['senha']),
            'cpf' => $dados['cpf'],
            'status' => $dados['status'] ?? 'pendente'
        ]);
    }
    
    // Atualizar dados do síndico
    public function atualizarSindico($id, $dados) {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, cpf = :cpf";
        $params = [
            'id' => $id,
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'cpf' => $dados['cpf']
        ];
        
        // Se foi fornecida uma nova senha
        if (!empty($dados['senha'])) {
            $sql .= ", senha = :senha";
            $params['senha'] = hash('sha256', $dados['senha']);
        }
        
        $sql .= " WHERE id = :id AND tipo = 'sindico'";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    
    // ==================== GERENCIAMENTO DE MORADORES ====================
    
    // Buscar todos os moradores
    public function getMoradores($busca = '') {
        $sql = "SELECT * FROM usuarios WHERE tipo = 'morador'";
        $params = [];
        
        if (!empty($busca)) {
            $sql .= " AND (nome LIKE :busca OR email LIKE :busca)";
            $params['busca'] = "%{$busca}%";
        }
        
        $sql .= " ORDER BY nome ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Buscar morador por ID
    public function getMoradorById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = :id AND tipo = 'morador'");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Atualizar status do morador
    public function atualizarStatusMorador($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET status = :status WHERE id = :id AND tipo = 'morador'");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }
    
    // ==================== FUNÇÕES GERAIS ====================
    
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
    
    // Obter estatísticas do dashboard
    public function getEstatisticas() {
        $stats = [];
        
        // Total de usuários
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM usuarios");
        $stats['total_usuarios'] = $stmt->fetchColumn();
        
        // Usuários por tipo
        $stmt = $this->pdo->query("SELECT tipo, COUNT(*) as total FROM usuarios GROUP BY tipo");
        $tipoStats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        $stats['total_admins'] = $tipoStats['admin'] ?? 0;
        $stats['total_sindicos'] = $tipoStats['sindico'] ?? 0;
        $stats['total_moradores'] = $tipoStats['morador'] ?? 0;
        
        // Usuários pendentes
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM usuarios WHERE status = 'pendente'");
        $stats['usuarios_pendentes'] = $stmt->fetchColumn();
        
        // Pautas ativas (se a tabela existir)
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM pautas WHERE data_fim > NOW()");
            $stats['pautas_ativas'] = $stmt->fetchColumn();
        } catch (PDOException $e) {
            $stats['pautas_ativas'] = 0;
        }
        
        // Total de votos (se a tabela existir)
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM votos");
            $stats['total_votos'] = $stmt->fetchColumn();
        } catch (PDOException $e) {
            $stats['total_votos'] = 0;
        }
        
        return $stats;
    }
    
    // Obter atividades recentes
    public function getAtividadesRecentes($limit = 10) {
        try {
            $atividades = [];
            
            // Últimos usuários cadastrados
            $stmt = $this->pdo->query("
                SELECT nome as usuario, 'Cadastro realizado' as acao, criado_em as data, 'sucesso' as status 
                FROM usuarios 
                ORDER BY criado_em DESC 
                LIMIT 5
            ");
            $cadastros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Tentar buscar últimos votos (se a tabela existir)
            try {
                $stmt = $this->pdo->query("
                    SELECT u.nome as usuario, CONCAT('Votou na pauta: ', p.titulo) as acao, v.criado_em as data, 'sucesso' as status
                    FROM votos v 
                    JOIN usuarios u ON v.id_morador = u.id 
                    JOIN pautas p ON v.id_pauta = p.id 
                    ORDER BY v.criado_em DESC 
                    LIMIT 5
                ");
                $votos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $atividades = array_merge($cadastros, $votos);
            } catch (PDOException $e) {
                $atividades = $cadastros;
            }
            
            // Ordenar por data (mais recente primeiro)
            usort($atividades, function($a, $b) {
                return strtotime($b['data']) - strtotime($a['data']);
            });
            
            // Limitar resultados
            $atividades = array_slice($atividades, 0, $limit);
            
            // Formatar datas
            foreach ($atividades as &$atividade) {
                $atividade['data'] = date('d/m/Y H:i', strtotime($atividade['data']));
            }
            
            return $atividades;
            
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>
