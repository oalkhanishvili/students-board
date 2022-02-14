<?php

namespace Oto\SchoolGrade\Database;

use PDO;
use PDOException;

class Connector {
    /**
     * @var PDO
     */
    private $connection;
    /**
     * @var false|\PDOStatement
     */
    private $result;

    private static $instance;

    public static function getInstance()
    {
        self::$instance = self::$instance ?: new self();
        return self::$instance;
    }

    public function __construct()
    {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=' . DB_PORT;

        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);

        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return print_r($e->getMessage());
        }
    }

    public function query($query)
    {
        $this->result = $this->connection->prepare($query);
        return $this;
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {

            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->result->bindValue($param, $value, $type);
        return $this;
    }

    public function first()
    {
        $this->execute();
        return $this->result->fetch(PDO::FETCH_ASSOC);
    }

    public function all()
    {
        $this->execute();
        return $this->result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count()
    {
        $this->execute();
        return $this->result->rowCount();
    }
    public function lastId()
    {
        return $this->connection->lastInsertId();
    }
    public function execute()
    {
        try {
            return $this->result->execute();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }
}

