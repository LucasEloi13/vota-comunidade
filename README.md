# Vota Comunidade

Sistema de votação comunitária para condomínios.

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