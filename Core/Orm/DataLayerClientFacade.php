<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare (strict_types = 1);

namespace Abc\Orm;

use Abc\Orm\ClientRepository\ClientRepository;
use Abc\Orm\ClientRepository\ClientRepositoryFactory;
use Abc\Utility\Log;

class DataLayerClientFacade
{

    protected string $clientIdentifier;
    protected string $tableSchema;
    protected string $tableSchemaID;

    /**
     * Final class which ties the entire data layer together. The data layer factory
     * is responsible for creating an object of all the component factories and injecting
     * the relevant parameters/arguments. ie the query builder factory, entity manager
     * factory and the data mapper factory.
     */
    public function __construct(string $clientIdentifier, string $tableSchema, string $tableSchemaID)
    {
        $this->clientIdentifier = $clientIdentifier;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    /**
     * Returns the client repository object which allows external and internal 
     * component to use the methods within.
     */
    public function getClientRepository(): Object
    {
        $factory = new ClientRepositoryFactory($this->clientIdentifier, $this->tableSchema, $this->tableSchemaID);
        if ($factory) {
            $client = $factory->create(ClientRepository::class);
            if ($client) {
                return $client;
            }
            Log::evo_log('Couldn\'t create ' . ClientRepository::class, ERROR_LOG);
            exit;
        }
        Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
        exit;
    }
}
