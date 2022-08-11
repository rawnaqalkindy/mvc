<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Orm\DataMapper;

use Abc\ErrorHandler\ErrorHandler;
use Abc\Orm\Drivers\DatabaseDriverInterface;
use Abc\Orm\DatabaseTransaction\DatabaseTransaction;
use Abc\Utility\Log;
use Exception;
use PDOStatement;
use PDO;

class DataMapper extends DatabaseTransaction implements DataMapperInterface
{
    private DatabaseDriverInterface $dbh;
    private PDOStatement $statement;

    public function __construct(DatabaseDriverInterface $dbh)
    {
        $this->dbh = $dbh;
        parent::__construct($this->dbh); /* Pass to DatabaseTransaction class */
    }

    /**
     * Check the incoming $value isn't empty else throw an exception
     */
    private function isEmpty($value, string $errorMessage = null)
    {
        if (empty($value)) {
            Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG, $errorMessage);
            exit;
        }
    }

    /**
     * Check the incoming argument $value is an array else throw an exception
     */
    private function isArray(array $value)
    {
        if (!is_array($value)) {
            ErrorHandler::exceptionHandler(new Exception('Your argument needs to be an array'));
            exit;
        }
    }

    public function getConnection(): DatabaseDriverInterface
    {
        return $this->dbh;
    }

    public function prepare(string $sqlQuery): self
    {
        $this->isEmpty($sqlQuery, 'Invalid or empty query string passed.');
        $this->statement = $this->dbh->open()->prepare($sqlQuery);
        return $this;
    }

    public function bind($value): int
    {
        switch ($value) {
            case is_bool($value):
                return PDO::PARAM_BOOL;
                break;
            case intval($value):
                return PDO::PARAM_INT;
                break;
            case is_null($value):
                return PDO::PARAM_NULL;
                break;
            default:
                return PDO::PARAM_STR;
                break;
        }
    }

    public function bindParameters(array $fields, bool $isSearch = false): self
    {
        $this->isArray($fields);
        if (is_array($fields)) {
            $type = ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);
            if ($type) {
                return $this;
            }
        }
        Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
        exit;
    }

    /**
     * Binds a value to a corresponding name or question mark placeholder in the SQL
     * statement that was used to prepare the statement
     */
    protected function bindValues(array $fields): PDOStatement
    {
        $this->isArray($fields); // don't need
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key, $value, $this->bind($value));
        }
        return $this->statement;
    }

    /**
     * Binds a value to a corresponding name or question mark placeholder
     * in the SQL statement that was used to prepare the statement. Similar to
     * above but optimised for search queries
     */
    protected function bindSearchValues(array $fields): PDOStatement
    {
        $this->isArray($fields); // don't need
        foreach ($fields as $key => $value) {
            $this->statement->bindValue(':' . $key,  '%' . $value . '%', $this->bind($value));
        }
        return $this->statement;
    }

    public function execute(): bool
    {
        if ($this->statement)
            return $this->statement->execute();
    }

    public function numRows(): int
    {
        if ($this->statement)
            return $this->statement->rowCount();
    }

    public function result(): Object
    {
        if ($this->statement)
            return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    public function results(): array
    {
        if ($this->statement)
            return $this->statement->fetchAll();
    }

    public function column()
    {
        if ($this->statement)
            return $this->statement->fetchColumn();
    }

    public function columns(): array
    {
        if ($this->statement)
            return $this->statement->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getLastId(): int
    {
        if ($this->dbh->open()) {
            $lastID = $this->dbh->open()->lastInsertId();
            if (!empty($lastID)) {
                return intval($lastID);
            }
        }
    }

    /**
     * Returns the query condition merged with the query parameters
     */
    public function buildQueryParameters(array $conditions = [], array $parameters = []): array
    {
        return (!empty($parameters) || (!empty($conditions)) ? array_merge($conditions, $parameters) : $parameters);
    }

    /**
     * Persist queries to database
     */
    public function persist(string $sqlQuery, array $parameters)
    {
//        echo $sqlQuery;
        //$this->start();
        try {
            $this->prepare($sqlQuery)->bindParameters($parameters)->execute();
            //$this->commit();
        } catch (Exception $e) {
            //$this->revert();
            ErrorHandler::exceptionHandler(new Exception($e->getMessage()));
            exit;
        }
    }

    /**
     * Quickly execute commands through command line.
     */
    public function exec(string $statement): void
    {
        $this->start();
        try {
            $this->dbh->open()->exec($statement);
            $this->commit();
        } catch (Exception $e) {
            $this->revert();
            ErrorHandler::exceptionHandler(new Exception($e->getMessage()));
            exit;
        }

    }

}
