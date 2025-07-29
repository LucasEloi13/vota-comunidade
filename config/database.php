<?php
$dotenv = parse_ini_file(__DIR__ . '/../.env');

try {
    $pdo = new PDO(
        "mysql:host={$dotenv['DB_HOST']};port={$dotenv['DB_PORT']};dbname={$dotenv['DB_DATABASE']}",
        $dotenv['DB_USERNAME'],
        $dotenv['DB_PASSWORD']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco: " . $e->getMessage());
}
