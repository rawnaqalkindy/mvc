<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Orm\Drivers;

use Abc\ErrorHandler\ErrorHandler;
use Exception;
use PDO;
use PDOException;

class SqliteDatabaseConnection extends AbstractDatabaseDriver
{

    /** @var string $driver */
    protected const PDO_DRIVER = 'sqlite';
    private object $environment;
    private string $pdoDriver;

    /**
     * Class constructor. piping the class properties to the constructor
     * method. The constructor will throw an exception if the database driver
     * doesn't match the class database driver.
     */
    public function __construct(object $environment, string $pdoDriver)
    {
        $this->environment = $environment;
        $this->pdoDriver = $pdoDriver;
        if (self::PDO_DRIVER !== $this->pdoDriver) {
            ErrorHandler::exceptionHandler(new Exception($pdoDriver . ' Invalid database driver pass requires ' . self::PDO_DRIVER . ' driver option to make your connection.'));
            exit;
        }
    }

    /**
     * Opens a new Sqlite database connection
     */
    public function open(): PDO
    {
        try {
            return new PDO(
                $this->credential->getDsn($this->driver),
                $this->credential->getDbUsername(),
                $this->credential->getDbPassword(),
                $this->params
            );
        } catch(PDOException $e) {
            ErrorHandler::exceptionHandler(new Exception($e->getMessage(), (int)$e->getCode()));
            exit;
        }

    }

}
