<?php

declare(strict_types=1);

// Caminho do SQLite.
$dbPath = __DIR__ . '/database.sqlite';

// Conecta ou cria o arquivo do banco automaticamente
$db = new PDO('sqlite:' . $dbPath);

// Cria a tabela vehicles
$db->exec("
    CREATE TABLE IF NOT EXISTS vehicles (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        plate TEXT NOT NULL,
        type TEXT NOT NULL,
        entry_time TEXT NOT NULL,
        leave_time TEXT NULL
    );
");

echo "Tabelas criadas com sucesso!\n";
