<?php
require_once __DIR__ . '/config/database.php';

try {
    // Ler e executar init.sql
    $initSQL = file_get_contents(__DIR__ . '/database/init.sql');
    $statements = explode(';', $initSQL);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "✅ Banco de dados inicializado com sucesso!\n";
    
    // Ler e executar seed.sql
    $seedSQL = file_get_contents(__DIR__ . '/database/seed.sql');
    $statements = explode(';', $seedSQL);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    
    echo "✅ Dados iniciais inseridos com sucesso!\n";
    echo "\n📋 Usuários de teste criados:\n";
    echo "- Admin: admin@vota.com / admin123\n";
    echo "- Síndico: joao@vota.com / sindico123\n";
    echo "- Morador: maria@vota.com / morador123\n";
    
} catch (PDOException $e) {
    echo "❌ Erro ao inicializar banco: " . $e->getMessage() . "\n";
}
?>
