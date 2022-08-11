<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Orm;


use Abc\ErrorHandler\ErrorHandler;
use Exception;

class DataLayerEnvironment
{
    protected Object $environmentConfiguration;
    private string $currentDriver;

    public function __construct(Object $environmentConfiguration, ?string $defaultDriver = null)
    {
        $this->environmentConfiguration = $environmentConfiguration;
        if (empty($defaultDriver)) {
            ErrorHandler::exceptionHandler(new Exception('Please specify your default database driver within the configuration file under the database settings'));
            exit;
        }
        $this->currentDriver  = $defaultDriver;
    }

    /**
     * Returns the base configuration as an array
     */
    public function getConfig() : array
    {
        return $this->environmentConfiguration->baseConfiguration();
    }

    /**
     * Get the user defined database connection array
     */
    public function getDatabaseCredentials() : array
    {
        $connectionArray = [];
        foreach ($this->getConfig() as $credential) {
            if (!array_key_exists($this->currentDriver, $credential)) {
                ErrorHandler::exceptionHandler(new Exception('Unsupported database driver. ' . $this->currentDriver));
                exit;
            } else {
                $connectionArray = $credential[$this->currentDriver];
            }
        }
        return $connectionArray;
    }

    /**
     * Returns the currently selected database dsn connection string
     */
    public function getDsn() : string
    {
        return $this->getDatabaseCredentials()['dsn'];
    }

    /**
     * Returns the currently selected database username from the connection string
     */
    public function getDbUsername() : string
    {
        return $this->getDatabaseCredentials()['username'];
    }

    /**
     * Returns the currently selected database password from the connection string
     */
    public function getDbPassword() : string
    {
        return $this->getDatabaseCredentials()['password'];
    }
}
