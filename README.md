# Vota Comunidade

Sistema de votação comunitária para condomínios.

## Estrutura do Projeto

```
├── index.php              # Arquivo principal com roteamento
├── setup.php              # Script de configuração do banco
├── .env                   # Variáveis de ambiente
├── .htaccess              # Configuração do Apache
├── app/
│   ├── api/
│   │   ├── dashboard_stats.php
│   │   └── dashboard_atividades.php
│   ├── controllers/
│   │   └── AuthController.php
│   ├── models/
│   └── views/
│       ├── login.php
│       ├── dashboard.php
│       └── admin_dashboard.php
├── config/
│   └── database.php       # Configuração do banco
├── database/
│   ├── init.sql          # Script de criação das tabelas
│   └── seed.sql          # Dados iniciais
└── public/               # Arquivos públicos (CSS, JS, imagens)
```

## Configuração

1. Configure o arquivo `.env` com suas credenciais do banco:
```
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=vota_comunidade
DB_USERNAME=root
DB_PASSWORD=root
```

2. Execute o script de configuração:
```bash
php setup.php
```

## Usuários Padrão

- **Admin:** admin@vota.com / admin123
- **Síndico:** joao@vota.com / sindico123
- **Morador:** maria@vota.com / morador123

## Funcionalidades

### Admin
- Dashboard administrativo completo
- Gerenciar síndicos e moradores
- Gerenciar condomínios
- Visualizar resultados de votações

### Síndico
- Criar e gerenciar pautas
- Visualizar relatórios de votação

### Morador
- Participar de votações
- Visualizar pautas ativas

## Tecnologias

- PHP 8.1+
- MySQL/MariaDB
- Bootstrap 5
- HTML5/CSS3
- JavaScript