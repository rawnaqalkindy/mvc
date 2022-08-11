<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Orm\DatabaseTransaction;

use Abc\ErrorHandler\ErrorHandler;
use Exception;
use PDOException;
use LogicException;
use Abc\Orm\Drivers\DatabaseDriverInterface;

class DatabaseTransaction implements DatabaseTransactionInterface
{
    private DatabaseDriverInterface $db;
    private int $transactionCounter = 0;

    /**
     * Main class constructor method which accepts the database connection object
     * which is then pipe to the class property (db)
     */
    public function __construct(DatabaseDriverInterface $db)
    {
        $this->db = $db;
        if (!$this->db) {
            ErrorHandler::exceptionHandler(new Exception('No Database connection was detected.'), CRITICAL_LOG);
            exit;
//            throw new LogicException();
        }
    }

    public function start(): bool
    {
        try {
            if ($this->db) {
                if (!$this->transactionCounter++) {
                    return $this->db->open()->beginTransaction();
                }
                return $this->db->open()->beginTransaction();
            }
        } catch (Exception $e) {
            ErrorHandler::exceptionHandler(new Exception($e->getMessage()), CRITICAL_LOG);
            exit;
//            throw new Exception();
        }
    }

    public function commit(): bool
    {
        try {
            if ($this->db) {
                if (!$this->transactionCounter) {
                    return $this->db->open()->commit();
                }
                return $this->transactionCounter >= 0;
            }
        } catch (Exception $e) {
            ErrorHandler::exceptionHandler(new Exception($e->getMessage()), CRITICAL_LOG);
            exit;
//            throw new Exception($e->getMessage());
        }
    }

    public function revert(): bool
    {
        try {
            if ($this->db) {
                if ($this->transactionCounter >= 0) {
                    $this->transactionCounter = 0;
                    return $this->db->open()->rollBack();
                }
                $this->transactionCounter = 0;
                return false;
            }
        } catch (Exception $e) {
            ErrorHandler::exceptionHandler(new Exception($e->getMessage()), CRITICAL_LOG);
            exit;
//            throw new Exception($e->getMessage());
        }
    }
}
