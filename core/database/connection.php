<?php

// Inclui o arquivo de configuração do banco de dados
$dbConfig = require __DIR__ . '/dbconfig.php';

// Função para criar ou retornar a conexão PDO
function createPDOConnection($dbConfig)
{
    static $pdo = null; // A conexão é armazenada estaticamente

    if ($pdo === null) {
        try {
            // Usa extract() para criar variáveis a partir das chaves do array $dbConfig
            extract($dbConfig);

            // Define o DSN de acordo com o driver usando o match
            $dsn = match ($driver) {
                'mysql'    => "mysql:host={$host};port={$port};dbname={$database};charset={$charset}",
                'pgsql'    => "pgsql:host={$host};port={$port};dbname={$database}",
                'sqlite'   => "sqlite:{$database}",
                'sqlsrv'   => "sqlsrv:Server={$host},{$port};Database={$database}", // SQL Server usa vírgula para a porta
                'oci'      => "oci:dbname=//$host:{$port}/{$database};charset={$charset}", // Oracle
                'firebird' => "firebird:dbname={$host}/{$port}:{$database}",
                default    => throw new Exception("Driver de banco de dados não suportado: {$driver}"),
            };

            // Cria a conexão PDO
            $pdo = new PDO($dsn, $username, $password, $options);

        } catch (PDOException $e) {
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    return $pdo; // Retorna a conexão PDO
}

// Retorna a conexão com o banco de dados ao incluir o arquivo
return createPDOConnection($dbConfig);
