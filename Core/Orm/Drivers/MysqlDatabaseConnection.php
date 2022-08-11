<?php

namespace Abc\Orm\Drivers;

use Abc\ErrorHandler\ErrorHandler;
use Abc\Orm\Drivers\AbstractDatabaseDriver;
use Exception;
use PDO;
use PDOException;

class MysqlDatabaseConnection extends AbstractDatabaseDriver
{

    protected string $pdoDriver;

    protected const PDO_DRIVER = 'mysql';
    private object $environment;

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
     * Opens a new Mysql database connection
     */
    public function open(): PDO
    {
        try {
            return new PDO(
                $this->environment->getDsn(),
                $this->environment->getDbUsername(),
                $this->environment->getDbPassword(),
                $this->params
            );
        } catch (PDOException $e) {
            ErrorHandler::exceptionHandler(new Exception($e->getMessage(), (int)$e->getCode()));
            exit;
        }
    }
}