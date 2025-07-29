#!/bin/bash

# Script de inicialização da aplicação Vota Comunidade
# Autor: Sistema Vota Comunidade
# Data: $(date +%Y-%m-%d)

echo "🚀 Iniciando aplicação Vota Comunidade..."
echo "========================================"

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Função para imprimir mensagens coloridas
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCESSO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[AVISO]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERRO]${NC} $1"
}

# Verificar se o PHP está instalado
print_status "Verificando instalação do PHP..."
if ! command -v php &> /dev/null; then
    print_error "PHP não está instalado. Por favor, instale o PHP 8.1 ou superior."
    exit 1
fi

PHP_VERSION=$(php -v | head -n1 | cut -d' ' -f2 | cut -d'.' -f1,2)
print_success "PHP $PHP_VERSION encontrado"

# Verificar se o arquivo .env existe
print_status "Verificando configurações..."
if [ ! -f ".env" ]; then
    print_error "Arquivo .env não encontrado. Por favor, configure suas variáveis de ambiente."
    exit 1
fi
print_success "Arquivo .env encontrado"

# Testar conexão com banco de dados
print_status "Testando conexão com banco de dados..."
php -r "
try {
    require_once 'config/database.php';
    echo 'OK';
} catch (Exception \$e) {
    echo 'ERRO: ' . \$e->getMessage();
    exit(1);
}
" > /tmp/db_test.log 2>&1

if grep -q "ERRO" /tmp/db_test.log; then
    print_error "Falha na conexão com banco de dados:"
    cat /tmp/db_test.log
    print_warning "Verifique suas configurações no arquivo .env"
    exit 1
fi
print_success "Conexão com banco de dados estabelecida"

# Verificar se as tabelas existem
print_status "Verificando estrutura do banco de dados..."
TABLE_COUNT=$(php -r "
require_once 'config/database.php';
try {
    \$stmt = \$pdo->query('SHOW TABLES');
    echo \$stmt->rowCount();
} catch (Exception \$e) {
    echo '0';
}
")

if [ "$TABLE_COUNT" -eq 0 ]; then
    print_warning "Banco de dados vazio. Executando script de inicialização..."
    php setup.php
    if [ $? -eq 0 ]; then
        print_success "Banco de dados inicializado com sucesso"
    else
        print_error "Falha ao inicializar banco de dados"
        exit 1
    fi
else
    print_success "Estrutura do banco de dados OK ($TABLE_COUNT tabelas encontradas)"
fi

# Verificar porta disponível
PORT=8080
print_status "Verificando disponibilidade da porta $PORT..."
if lsof -Pi :$PORT -sTCP:LISTEN -t >/dev/null 2>&1; then
    print_warning "Porta $PORT já está em uso. Tentando porta 8081..."
    PORT=8081
    if lsof -Pi :$PORT -sTCP:LISTEN -t >/dev/null 2>&1; then
        print_warning "Porta $PORT também está em uso. Tentando porta 8082..."
        PORT=8082
    fi
fi

# Iniciar servidor
print_success "Iniciando servidor web na porta $PORT..."
echo ""
echo "🌐 Aplicação disponível em: http://localhost:$PORT"
echo ""
echo "👥 Usuários de teste:"
echo "   • Admin: admin@vota.com / admin123"
echo "   • Síndico: joao@vota.com / sindico123"
echo "   • Morador: maria@vota.com / morador123"
echo ""
echo "⏹️  Para parar o servidor, pressione Ctrl+C"
echo "========================================"
echo ""

# Abrir navegador (se disponível)
if command -v "$BROWSER" &> /dev/null && [ -n "$BROWSER" ]; then
    print_status "Abrindo navegador..."
    "$BROWSER" "http://localhost:$PORT" &
fi

# Iniciar servidor PHP
php -S 0.0.0.0:$PORT

# Cleanup
rm -f /tmp/db_test.log
