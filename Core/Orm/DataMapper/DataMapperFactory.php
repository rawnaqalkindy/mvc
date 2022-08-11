<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only.
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Orm\DataMapper;

use Abc\Orm\DataLayerEnvironment;
use Abc\Utility\Log;

class DataMapperFactory
{
    /**
     * Creates the data mapper object and inject the dependency for this object. We are also
     * creating the DatabaseConnection Object and injecting the environment object. Which will
     * expose the environment methods with the database connection class.
     */
    public function create(string $databaseDriverFactory, DataLayerEnvironment $environment): DataMapperInterface
    {
        $params = $this->resolvedDatabaseParameters();
        $dbObject = (new $databaseDriverFactory())->create($environment, $params['class'], $params['driver']);
        return new DataMapper($dbObject);
    }

    /**
     * Return the application parameters as they were defined within the config
     * yaml file
     */
    private function resolvedDatabaseParameters(): array
    {
        $database = DATABASE;
        if (is_array($database) && count($database) > 0) {
            foreach ($database['drivers'] as $driver => $class) {
                if (isset($driver) && $driver === $database['default_driver']) {
                    return array_merge($class, ['driver' => $driver]);
                }
            }
        }
        Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
        exit;
    }
}
