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

class DatabaseDriverFactory
{
    /**
     * Create and return the database driver object. Passing the object environment and
     * default database driver to the database driver constructor method.
     */
    public function create(object $environment, ?string $dbDriverConnection, string $pdoDriver): DatabaseDriverInterface
    {
//        if (is_object($environment)) {
            $dbConnection = ($dbDriverConnection !==null) ? new $dbDriverConnection($environment, $pdoDriver) : new MysqlDatabaseConnection($environment, $pdoDriver);
            if (!$dbConnection instanceof DatabaseDriverInterface) {
                ErrorHandler::exceptionHandler(new Exception());
                exit;
            }

            return $dbConnection;
//        }
    }


}