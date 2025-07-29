# Vota Comunidade

Sistema de votação comunitária para condomínios.

## 📚 Documentação

- **[📖 Documentação Completa](DOCUMENTATION.md)** - Guia detalhado de desenvolvimento
- **[🏗️ Arquitetura MVC](DOCUMENTATION.md#arquitetura-mvc)** - Como o sistema está estruturado
- **[🚀 Como Criar Funcionalidades](DOCUMENTATION.md#como-criar-novas-funcionalidades)** - Passo a passo para desenvolvedores

## ⚡ Início Rápido

## Configuração

1. Configure o arquivo `.env` com suas credenciais do banco:
```
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=vota_comunidade
DB_USERNAME=root
DB_PASSWORD=root
```

2. Execute o script de inicialização (recomendado):
```bash
./start.sh
```

**OU** execute manualmente:
```bash
php setup.php
php -S 0.0.0.0:8080
```

## Inicialização Rápida

Para iniciar a aplicação de forma automatizada, use o script de inicialização:

```bash
# Torna o script executável (primeira vez apenas)
chmod +x start.sh

# Inicia a aplicação
./start.sh
```

O script irá:
- ✅ Verificar dependências (PHP, banco de dados)
- ✅ Testar conexão com o banco
- ✅ Inicializar estrutura do banco (se necessário)
- ✅ Encontrar uma porta disponível (8080, 8081 ou 8082)
- ✅ Iniciar o servidor web
- 🌐 Abrir o navegador automaticamente (se disponível)

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

## 📁 Estrutura do Projeto

```
├── index.php              # Ponto de entrada e roteamento
├── setup.php              # Script de configuração do banco
├── DOCUMENTATION.md       # Documentação completa do projeto
├── app/
│   ├── controllers/       # Controladores (MVC)
│   ├── models/           # Modelos (MVC)
│   └── views/            # Visualizações (MVC)
├── config/
│   └── database.php      # Configuração do banco
└── database/
    ├── init.sql         # Script de criação das tabelas
    └── seed.sql         # Dados iniciais
```

## 🔗 Links Úteis

- **[Documentação Completa](DOCUMENTATION.md)** - Guia detalhado para desenvolvedores
- **[GitHub Repository](https://github.com/LucasEloi13/vota-comunidade)**