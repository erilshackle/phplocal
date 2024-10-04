<?php

namespace backend\database;

use PDOException;
use PDOStatement;
use PDO;

final class Database
{
    private static $pdo = null;

    /**
     * Obtém a conexão PDO do arquivo connection.php
     */
    private static function getConnection()
    {
        if (self::$pdo === null) {
            // Inclui o arquivo de conexão, que retorna a instância PDO
            self::$pdo = require __DIR__ . '/connection.php';
        }
        return self::$pdo;
    }

    /**
     * Faz o binding dos parâmetros, aceitando tanto parâmetros nomeados (:param) quanto templates (?)
     *
     * @param PDOStatement $stmt O statement preparado.
     * @param array $params Os parâmetros a serem vinculados.
     */
    private static function bindParams($stmt, $params)
    {
        // Se o array é associativo, usa parâmetros nomeados
        if (is_assoc($params)) {
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
        } else {
            // Para parâmetros template (?), vincula de acordo com a posição
            foreach ($params as $index => $value) {
                $stmt->bindValue($index + 1, $value); // PDO usa índices 1-based
            }
        }
    }

    /**
     * Executa uma query SQL simples (SELECT) e retorna os resultados.
     *
     * @param string $sql A query SQL a ser executada.
     * @param array $params Parâmetros opcionais para a query preparada.
     * @return array O resultado da consulta.
     */
    public static function query($sql, $params = [])
    {
        $stmt = self::getConnection()->prepare($sql);
        self::bindParams($stmt, $params);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return count($result) > 1 ? $result : $result[0];
    }

    /**
     * Executa um comando SQL (INSERT, UPDATE, DELETE) e retorna o número de linhas afetadas.
     *
     * @param string $sql O comando SQL a ser executado.
     * @param array $params Parâmetros opcionais para a query preparada.
     * @return int O número de linhas afetadas.
     */
    public static function execute($sql, $params = [])
    {
        $stmt = self::getConnection()->prepare($sql);
        self::bindParams($stmt, $params);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Prepara e executa uma consulta SQL, retornando o objeto PDOStatement.
     *
     * @param string $sql A query SQL a ser preparada.
     * @param array $params Parâmetros opcionais para a query preparada.
     * @return PDOStatement O objeto preparado e executado.
     */
    public static function prepare($sql, $params = [])
    {
        $stmt = self::getConnection()->prepare($sql);
        self::bindParams($stmt, $params);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Inicia uma transação.
     */
    public static function beginTransaction()
    {
        self::getConnection()->beginTransaction();
    }

    /**
     * Confirma a transação.
     */
    public static function commit()
    {
        self::getConnection()->commit();
    }

    /**
     * Reverte a transação.
     */
    public static function rollBack()
    {
        self::getConnection()->rollBack();
    }

    /**
     * Executa uma operação de banco de dados dentro de uma transação.
     *
     * @param callable $executeInTransaction Função a ser executada dentro da transação.
     * @return mixed O resultado da execução da função fornecida, ou null em caso de erro.
     */
    public static function callTransaction(callable $executeInTransaction)
    {
        self::beginTransaction(); // Inicia a transação
        $result = null; // Inicializa a variável de resultado
        try {
            $result = $executeInTransaction(); // Executa a função fornecida
            self::commit(); // Confirma a transação
        } catch (PDOException $ex) {
            self::rollBack(); // Reverte a transação em caso de erro
        }
        return $result; // Retorna o resultado da função ou null em caso de falha
    }

}

/**
 * Verifica se um array é associativo.
 *
 * @param array $array O array a ser verificado.
 * @return bool Retorna true se o array for associativo.
 */
function is_assoc(array $array)
{
    return array_keys($array) !== range(0, count($array) - 1);
}
