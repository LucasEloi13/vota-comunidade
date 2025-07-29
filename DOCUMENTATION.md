# 📚 Documentação do Sistema Vota Comunidade

## 📖 Índice
1. [Visão Geral](#visão-geral)
2. [Arquitetura MVC](#arquitetura-mvc)
3. [Sistema de Roteamento](#sistema-de-roteamento)
4. [Estrutura de Pastas](#estrutura-de-pastas)
5. [Modelos (Models)](#modelos-models)
6. [Controladores (Controllers)](#controladores-controllers)
7. [Visualizações (Views)](#visualizações-views)
8. [Como Criar Novas Funcionalidades](#como-criar-novas-funcionalidades)
9. [Exemplos Práticos](#exemplos-práticos)
10. [Boas Práticas](#boas-práticas)

---

## 🔍 Visão Geral

O **Vota Comunidade** é um sistema de votação comunitária desenvolvido em PHP com arquitetura MVC (Model-View-Controller). O sistema permite que administradores gerenciem síndicos e moradores, síndicos criem pautas de votação, e moradores participem das votações.

### Principais Características:
- ✅ **Arquitetura MVC** limpa e organizada
- ✅ **Sistema de roteamento** customizado
- ✅ **Autenticação** com três tipos de usuário (admin, síndico, morador)
- ✅ **Interface responsiva** com Bootstrap 5
- ✅ **Banco de dados MySQL** com PDO
- ✅ **Gestão de sessões** para controle de acesso

---

## 🏗️ Arquitetura MVC

O sistema segue o padrão **Model-View-Controller (MVC)**:

```
┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│    MODEL    │    │ CONTROLLER  │    │    VIEW     │
│             │    │             │    │             │
│ - Admin     │◄───┤ - Auth      │◄───┤ - Login     │
│ - Usuario   │    │ - Sindico   │    │ - Dashboard │
│             │    │             │    │ - Gerenciar │
└─────────────┘    └─────────────┘    └─────────────┘
       ▲                   ▲                   ▲
       │                   │                   │
       └─── Banco de ──────┼──── Roteamento ───┘
            Dados          │
                          │
                ┌─────────────┐
                │   index.php │
                │ (Entry Point)│
                └─────────────┘
```

### **Model (Modelo)**
- Responsável pela **lógica de negócio** e **acesso aos dados**
- Interage diretamente com o **banco de dados**
- Contém regras de validação e manipulação de dados

### **View (Visualização)**
- Responsável pela **apresentação** dos dados
- Contém **HTML, CSS e JavaScript**
- Recebe dados do Controller e os exibe ao usuário

### **Controller (Controlador)**
- **Intermediário** entre Model e View
- Processa **requisições do usuário**
- Coordena a lógica da aplicação

---

## 🛣️ Sistema de Roteamento

O roteamento é gerenciado pelo arquivo `index.php` na raiz do projeto:

### Como Funciona:
1. **Todas as requisições** passam pelo `index.php`
2. A função `route()` analisa a **URL** e direciona para o arquivo correto
3. O arquivo `.htaccess` redireciona URLs amigáveis

### Exemplo de Rota:
```php
// URL: /gerenciar-sindicos
case '/gerenciar-sindicos':
    // Verificar permissões
    if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
        header("Location: /login");
        exit;
    }
    // Incluir o controller
    include __DIR__ . '/app/controllers/SindicoController.php';
    break;
```

### Tipos de Rota:
- **Simples**: Redirecionam para uma view específica
- **Com Controller**: Processam lógica antes de exibir a view
- **Com Autenticação**: Verificam permissões antes de prosseguir

---

## 📁 Estrutura de Pastas

```
vota-comunidade/
├── 📄 index.php              # Ponto de entrada e roteamento
├── 📄 setup.php              # Script de inicialização do banco
├── 📄 .env                   # Variáveis de ambiente
├── 📄 .htaccess              # Configuração do Apache
├── 📄 README.md              # Documentação básica
├── 📄 DOCUMENTATION.md       # Esta documentação
│
├── 📁 app/                   # Código da aplicação
│   ├── 📁 controllers/       # Controladores
│   │   ├── AuthController.php
│   │   └── SindicoController.php
│   │
│   ├── 📁 models/            # Modelos
│   │   ├── Admin.php
│   │   └── Usuario.php
│   │
│   └── 📁 views/             # Visualizações
│       ├── login.php
│       ├── dashboard.php
│       ├── admin_dashboard.php
│       ├── gerenciar_sindicos.php
│       ├── editar_sindico.php
│       └── criar_sindico.php
│
├── 📁 config/                # Configurações
│   └── database.php          # Conexão com banco
│
├── 📁 database/              # Scripts do banco
│   ├── init.sql              # Estrutura das tabelas
│   └── seed.sql              # Dados iniciais
│
└── 📁 public/                # Arquivos públicos
    ├── css/
    ├── js/
    └── images/
```

---

## 🗄️ Modelos (Models)

Os modelos são responsáveis pela **lógica de negócio** e **acesso aos dados**.

### Estrutura Base de um Model:

```php
<?php
require_once __DIR__ . '/../../config/database.php';

class MinhaClasse {
    private $pdo;
    
    public function __construct() {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }
    
    // Métodos para interagir com o banco
}
```

### Models Existentes:

#### **1. Admin.php**
**Propósito**: Funcionalidades administrativas

**Métodos principais**:
- `getSindicos($busca)` - Lista síndicos com filtro
- `criarSindico($dados)` - Cria novo síndico
- `atualizarStatusSindico($id, $status)` - Aprova/rejeita síndico
- `getEstatisticas()` - Dados para dashboard
- `getAtividadesRecentes()` - Atividades do sistema

#### **2. Usuario.php**
**Propósito**: Operações básicas de usuário

**Métodos principais**:
- `buscarPorEmail($email)` - Login
- `verificarCredenciais($email, $senha)` - Autenticação
- `criar($dados)` - Registro público
- `atualizarPerfil($id, $dados)` - Edição de perfil

---

## 🎮 Controladores (Controllers)

Os controladores processam **requisições** e coordenam **Models** e **Views**.

### Estrutura Base de um Controller:

```php
<?php
require_once __DIR__ . '/../models/MeuModel.php';

class MeuController {
    private $model;
    
    public function __construct() {
        $this->model = new MeuModel();
    }
    
    public function minhaAcao() {
        // 1. Processar dados da requisição
        $dados = $_POST['dados'] ?? '';
        
        // 2. Interagir com o Model
        $resultado = $this->model->buscarDados($dados);
        
        // 3. Incluir a View
        include __DIR__ . '/../views/minha_view.php';
    }
}

// Processar ação baseada na URL
$controller = new MeuController();
$acao = $_GET['acao'] ?? 'index';

switch ($acao) {
    case 'criar':
        $controller->criar();
        break;
    default:
        $controller->index();
        break;
}
```

### Controllers Existentes:

#### **1. AuthController.php**
**Propósito**: Autenticação de usuários

**Funcionalidades**:
- Processar login
- Validar credenciais
- Gerenciar sessões
- Redirecionamento baseado no tipo de usuário

#### **2. SindicoController.php**
**Propósito**: Gerenciamento de síndicos (admin only)

**Ações disponíveis**:
- `listar()` - Lista todos os síndicos
- `criar()` - Formulário e processamento de criação
- `editar()` - Formulário e processamento de edição
- `aprovar()` - Aprova síndico pendente
- `rejeitar()` - Rejeita síndico pendente

---

## 👁️ Visualizações (Views)

As views são responsáveis pela **apresentação** dos dados ao usuário.

### Estrutura Base de uma View:

```php
<?php
// Verificações de segurança
if (!isset($_SESSION['usuario'])) {
    header("Location: /login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minha Página - Vota Comunidade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos customizados */
    </style>
</head>
<body>
    <!-- Navbar padrão do sistema -->
    <nav class="navbar">...</nav>
    
    <!-- Conteúdo da página -->
    <div class="container">
        <!-- Dados vindos do Controller -->
        <?php foreach ($dados as $item): ?>
            <p><?= htmlspecialchars($item['nome']) ?></p>
        <?php endforeach; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

### Views Existentes:

#### **1. login.php**
- Formulário de autenticação
- Exibição de erros de login
- Redirecionamento pós-login

#### **2. admin_dashboard.php**
- Dashboard específico para administradores
- Cards de navegação para funcionalidades
- Design personalizado

#### **3. gerenciar_sindicos.php**
- Listagem de síndicos com filtros
- Ações de aprovar/rejeitar
- Links para edição

---

## 🚀 Como Criar Novas Funcionalidades

### **Passo 1: Planejamento**
1. **Defina o propósito** da funcionalidade
2. **Identifique as permissões** necessárias
3. **Planeje a estrutura** de dados (se nova)

### **Passo 2: Criar/Atualizar Model**
```php
// Em app/models/MeuModel.php
public function minhaNovaFuncao($parametros) {
    $sql = "SELECT * FROM tabela WHERE condicao = :param";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['param' => $parametros]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

### **Passo 3: Criar Controller**
```php
// Em app/controllers/MeuController.php
class MeuController {
    private $model;
    
    public function __construct() {
        $this->model = new MeuModel();
    }
    
    public function index() {
        $dados = $this->model->buscarTodos();
        include __DIR__ . '/../views/minha_view.php';
    }
    
    public function criar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Processar formulário
            $this->model->criar($_POST);
            header('Location: /minha-rota');
            exit;
        }
        
        // Exibir formulário
        include __DIR__ . '/../views/criar_item.php';
    }
}

// Processar ações
$controller = new MeuController();
$acao = $_GET['acao'] ?? 'index';

switch ($acao) {
    case 'criar':
        $controller->criar();
        break;
    default:
        $controller->index();
        break;
}
```

### **Passo 4: Criar Views**
```php
<!-- Em app/views/minha_view.php -->
<?php
if (!isset($_SESSION['usuario'])) {
    header("Location: /login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<!-- HTML da página -->
</html>
```

### **Passo 5: Adicionar Rota**
```php
// Em index.php
case '/minha-nova-rota':
    // Verificar permissões se necessário
    if (!isset($_SESSION['usuario'])) {
        header("Location: /login");
        exit;
    }
    include __DIR__ . '/app/controllers/MeuController.php';
    break;
```

### **Passo 6: Atualizar Navegação**
```php
<!-- Adicionar link na navbar das views relevantes -->
<a href="/minha-nova-rota">Minha Funcionalidade</a>
```

---

## 💡 Exemplos Práticos

### **Exemplo 1: Criar Gerenciamento de Moradores**

#### **1. Model** (`app/models/Admin.php` - já existe)
```php
// Métodos já criados:
public function getMoradores($busca = '') { ... }
public function getMoradorById($id) { ... }
public function atualizarStatusMorador($id, $status) { ... }
```

#### **2. Controller** (`app/controllers/MoradorController.php`)
```php
<?php
require_once __DIR__ . '/../models/Admin.php';

class MoradorController {
    private $adminModel;
    
    public function __construct() {
        $this->adminModel = new Admin();
    }
    
    public function listar() {
        $busca = $_GET['busca'] ?? '';
        $moradores = $this->adminModel->getMoradores($busca);
        include __DIR__ . '/../views/gerenciar_moradores.php';
    }
    
    public function aprovar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id && $this->adminModel->atualizarStatusMorador($id, 'ativo')) {
                $_SESSION['msg_success'] = 'Morador aprovado!';
            }
        }
        header('Location: /gerenciar-moradores');
        exit;
    }
}

$controller = new MoradorController();
$acao = $_GET['acao'] ?? 'listar';

switch ($acao) {
    case 'aprovar':
        $controller->aprovar();
        break;
    default:
        $controller->listar();
        break;
}
```

#### **3. View** (`app/views/gerenciar_moradores.php`)
```php
<!-- Similar à gerenciar_sindicos.php, mas adaptada para moradores -->
```

#### **4. Rota** (`index.php`)
```php
case '/gerenciar-moradores':
    if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
        header("Location: /login");
        exit;
    }
    include __DIR__ . '/app/controllers/MoradorController.php';
    break;
```

### **Exemplo 2: Sistema de Pautas (para Síndicos)**

#### **1. Model** (`app/models/Pauta.php`)
```php
<?php
require_once __DIR__ . '/../../config/database.php';

class Pauta {
    private $pdo;
    
    public function __construct() {
        require __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;
    }
    
    public function criar($dados) {
        $sql = "INSERT INTO pautas (titulo, descricao, data_inicio, data_fim, id_sindico) 
                VALUES (:titulo, :descricao, :data_inicio, :data_fim, :id_sindico)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($dados);
    }
    
    public function buscarPorSindico($idSindico) {
        $sql = "SELECT * FROM pautas WHERE id_sindico = :id_sindico ORDER BY data_inicio DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_sindico' => $idSindico]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
```

---

## ⚡ Quando Criar Novas Rotas

### **Criar Nova Rota Quando:**
1. **Nova página** precisa ser acessada
2. **Nova funcionalidade** requer URL específica
3. **Diferentes permissões** são necessárias
4. **APIs** precisam de endpoints específicos

### **Tipos de Rota:**

#### **1. Rota Simples (View apenas)**
```php
case '/sobre':
    include __DIR__ . '/app/views/sobre.php';
    break;
```

#### **2. Rota com Controller**
```php
case '/pautas':
    if (!isset($_SESSION['usuario'])) {
        header("Location: /login");
        exit;
    }
    include __DIR__ . '/app/controllers/PautaController.php';
    break;
```

#### **3. Rota com Permissões Específicas**
```php
case '/admin-only':
    if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
        header("Location: /login");
        exit;
    }
    include __DIR__ . '/app/controllers/AdminController.php';
    break;
```

#### **4. Rota de API (JSON)**
```php
case '/api/dados':
    header('Content-Type: application/json');
    if (!isset($_SESSION['usuario'])) {
        echo json_encode(['error' => 'Não autorizado']);
        exit;
    }
    include __DIR__ . '/app/api/dados.php';
    break;
```

---

## 📋 Boas Práticas

### **1. Segurança**
```php
// ✅ Sempre verificar permissões
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header("Location: /login");
    exit;
}

// ✅ Escapar dados de saída
echo htmlspecialchars($usuario['nome']);

// ✅ Usar prepared statements
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $id]);
```

### **2. Estrutura**
```php
// ✅ Um controller por funcionalidade principal
// AuthController, SindicoController, PautaController

// ✅ Models específicos para cada entidade
// Usuario, Admin, Pauta, Voto

// ✅ Views organizadas por funcionalidade
// auth/, admin/, sindico/, morador/
```

### **3. Nomenclatura**
```php
// ✅ Métodos descritivos
public function buscarPorEmail($email) { ... }
public function atualizarStatusSindico($id, $status) { ... }

// ✅ Variáveis claras
$usuariosAtivos = $admin->getUsuariosAtivos();
$pautasVencidas = $pauta->getPautasVencidas();
```

### **4. Tratamento de Erros**
```php
// ✅ Usar try-catch em operações críticas
try {
    $resultado = $this->pdo->prepare($sql);
    $resultado->execute($params);
} catch (PDOException $e) {
    $_SESSION['msg_error'] = 'Erro interno do sistema';
    header('Location: /erro');
    exit;
}

// ✅ Validar dados de entrada
if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['msg_error'] = 'Email inválido';
    return;
}
```

### **5. Performance**
```php
// ✅ Incluir apenas o necessário
require_once __DIR__ . '/config/database.php'; // Apenas quando necessário

// ✅ Usar LIMIT em consultas grandes
$sql = "SELECT * FROM usuarios LIMIT 50";

// ✅ Indexar campos de busca no banco
CREATE INDEX idx_email ON usuarios(email);
```

---

## 🎯 Próximos Passos Sugeridos

1. **Implementar Gerenciamento de Moradores**
2. **Criar Sistema de Pautas para Síndicos**
3. **Desenvolver Sistema de Votação**
4. **Adicionar Relatórios e Gráficos**
5. **Implementar Notificações**
6. **Adicionar Upload de Arquivos**
7. **Criar API REST completa**

---

**📝 Esta documentação deve ser atualizada sempre que novas funcionalidades forem adicionadas ao sistema.**
