<?php


/**
 * Arquivo de configuração do banco de dados (dbconfig.php) 
 * Este arquivo usa informacoes e variaveis da Variavel de ambente (arquivo .env)
 */ 
return [
    'driver' => $_ENV['DB_DRIVER'] ?? $_ENV['DB_TYPE'] ?? 'mysql',
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'port' => $_ENV['DB_PORT'] ?? '3306',
    'username' => $_ENV['DB_USER'] ?? '',              //? Usuario do banco de dados
    'password' => $_ENV['DB_PASS'] ?? '',               //? Password do banco de dados
    'database' => $_ENV['DB_NAME'] ?? '',               //? Nome do banco de dados

    # Charset (usado para MySQL e PostgreSQL)
    'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4', 

    # Collation (usado para MySQL)
    'collation' => $_ENV['DB_COLLATION'] ?? 'utf8mb4_general_ci', 

    // Opções adicionais para o PDO
    'options' => [ 
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Exceções em erros
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Busca por arrays associativos
        PDO::ATTR_EMULATE_PREPARES => false, // Desabilitar a emulação de consultas preparadas
    ]
];

/**
 ** DEFAULTS PORTS
 * MySQL: 3306
 * PostgreSQL: 5432
 * SQL Server: 1433
 * Oracle: 1521
 */