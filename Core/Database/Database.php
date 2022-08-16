<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended using it in production as it is.
 */

declare (strict_types = 1);

namespace Abc\Database;

use Abc\ErrorHandler\ErrorHandler;
use PDO;
use PDOException;

class Database
{
    private string $dbHost = 'localhost';
    private string $dbUname = 'root';
    private string $dbPass = '';
    private string $dbName = 'sample_db';
    private ?PDO $conn;
    public string $connection_status;

    protected array $params = [
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    public function getDbCredentials(): array
    {
        return [
            'host' => $this->dbHost,
            'user' => $this->dbUname,
            'password' => $this->dbPass,
            'db' => $this->dbName,
        ];
    }

    public function open(): ?PDO
    {
        try {
            $this->conn = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUname, $this->dbPass, $this->params);
            $this->connection_status = 'Connected';
            return $this->conn;
        } catch (PDOException $e) {
            $this->connection_status = 'Failed';
            ErrorHandler::exceptionHandler($e, CRITICAL_LOG);
            return null;
        }
    }

    private function close()
    {
        $this->conn = null;
    }

    public function __destruct()
    {
        $this->close();
    }
}
