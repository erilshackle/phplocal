<?php 

namespace core\database;
use PDO;
use Exception;

/**
 * DBModel should be used to be extended by classes that represents Database Table Entity
 * @use PDO to get the connection from database and process / executes some methods
 * @abstract must be extends Database Entity classes that shall have atributes of theirs fields
 * @example usage class User extends DBModel {  constructor{ parrent::__contructor('users); } }
 */
abstract class DBModel
{
    private static $pdo;
    protected string $tablename;       // Nome da tabela
    protected string $primaryKey;      // Nome do campo da chave primária

    /**
     * @param string $tablename
     * @param string $primaryKey
     */
    public function __construct(string $tablename = null, string $primaryKey = 'id')
    {
        if (!self::$pdo) {
            self::$pdo = require 'connection.php'; // Obtenção da conexão PDO
        }

        // Se não for fornecido um nome de tabela, usa o nome da classe filha com "s" no final
        $this->tablename = $tablename ?: strtolower(static::class) . 's';

        // Define o nome da chave primária
        $this->primaryKey = $primaryKey;
    }

    // Método para encontrar um registro pelo valor da chave primária
    public static function find($id)
    {
        $instance = new static(); // Cria uma nova instância da classe chamada
        $stmt = self::$pdo->prepare("SELECT * FROM {$instance->tablename} WHERE {$instance->primaryKey} = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
        return $stmt->fetch();
    }

    // Método para salvar um registro (inserir ou atualizar)
    public function save()
    {
        $fields = array_keys(get_object_vars($this));
        $placeholders = array_map(function($field) { return ":$field"; }, $fields);

        if (isset($this->{$this->primaryKey})) {
            // Atualizar registro existente
            $updateFields = implode(", ", array_map(function($field) { return "$field = :$field"; }, $fields));
            $sql = "UPDATE {$this->tablename} SET $updateFields WHERE {$this->primaryKey} = :primaryKey";
        } else {
            // Inserir novo registro
            $sql = "INSERT INTO {$this->tablename} (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $placeholders) . ")";
        }

        $stmt = self::$pdo->prepare($sql);
        foreach ($fields as $field) {
            $stmt->bindValue(":$field", $this->$field);
        }

        // Bind da chave primária para atualização
        if (isset($this->{$this->primaryKey})) {
            $stmt->bindValue(":primaryKey", $this->{$this->primaryKey});
        }

        $stmt->execute();

        // Se for uma inserção, pega o último ID inserido e atribui ao objeto
        if (!isset($this->{$this->primaryKey})) {
            $this->{$this->primaryKey} = self::$pdo->lastInsertId();
        }

        return $stmt;
    }

    // Método para deletar um registro (usando a chave primária)
    public function delete()
    {
        if (!isset($this->{$this->primaryKey})) {
            throw new Exception("ID must be set to delete a record.");
        }
        $stmt = self::$pdo->prepare("DELETE FROM {$this->tablename} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$this->{$this->primaryKey}]);
    }

    // Método opcional para obter o nome da tabela
    public function getTableName()
    {
        return $this->tablename;
    }
}
