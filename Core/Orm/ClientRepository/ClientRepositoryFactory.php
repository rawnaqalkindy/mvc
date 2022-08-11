<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare (strict_types = 1);

namespace Abc\Orm\ClientRepository;

use Abc\ErrorHandler\ErrorHandler;
use Abc\Orm\DataLayerFactory;
use Abc\Orm\DataLayerEnvironment;
use Abc\Orm\DataLayerConfiguration;
use Abc\Utility\Log;
use Exception;
use Symfony\Component\Dotenv\Dotenv;

class ClientRepositoryFactory
{
    protected string $tableSchema;
    protected string $tableSchemaID;
    protected string $crudIdentifier;

    public function __construct(string $crudIdentifier, string $tableSchema, string $tableSchemaID)
    {
        $this->crudIdentifier = $crudIdentifier;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    /**
     * Create the ClientRepository Object. Which is the middle layer that interacts with
     * the application using this framework. The data repository object will have 
     * the required dependency injected by default. Which is the data layer facade object
     * which is simple passing in the entity manager object which expose the crud methods
     */
    public function create(string $dataRepositoryString, ?array $dataLayerConfiguration = null) : ClientRepositoryInterface
    {
        
        $dataRepositoryObject = new $dataRepositoryString($this->buildEntityManager($dataLayerConfiguration));
        if (!$dataRepositoryObject instanceof ClientRepositoryInterface ) {
            ErrorHandler::exceptionHandler(new Exception($dataRepositoryString . ' is not a valid repository object'));
            exit;
        }
        return $dataRepositoryObject;
    }

    /**
     * Build entity manager which creates the data layer factory and passing in the
     * environment configuration array and symfony dotenv component. Which is used 
     * to set the database environment config.
     */
    public function buildEntityManager(?array $dataLayerConfiguration = null) : Object
    {
        $dataLayerEnvironment = new DataLayerEnvironment(
            new DataLayerConfiguration(
            Dotenv::class,
            ($dataLayerConfiguration !==null) ? $dataLayerConfiguration : NULL,
            ),
            DATABASE['default_driver'] /* second argument */

        );
        $factory = new DataLayerFactory($dataLayerEnvironment, $this->tableSchema, $this->tableSchemaID);
        if ($factory) {
            return $factory->dataEntityManagerObject();
        }
        Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
        exit;
    }

}
