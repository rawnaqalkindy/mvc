<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Orm\DataRepository;

use Abc\ErrorHandler\ErrorHandler;
use Abc\Orm\ClientRepository\ClientRepositoryFactory;
use Exception;

class DataRepositoryFactory
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
     * The client repository is an internal data layer which expose methods for internal
     * and external library data consumption. It also provides method for putting and dropping
     * data from the client specified data entities.
     */
    public function create(string $DataRepositoryString, ?array $dataLayerConfiguration = null) : DataRepositoryInterface
    {

        $DataRepositoryObject = new $DataRepositoryString($this->buildEntityManager($dataLayerConfiguration));
        if (!$DataRepositoryObject instanceof DataRepositoryInterface) {
            ErrorHandler::exceptionHandler(new Exception($DataRepositoryString . ' is not a valid repository object'));
            exit;
        }
        return $DataRepositoryObject;
    }

    /**
     * As this class is a carbon copy of the ClientRepositoryFactory. We are simple borrowing
     * this method and passing in the relevant arguments in for this class. This client class
     * was design to be used internal. Whilst the ClientRepositoryFactory Object is acting as
     * the middle man for the frontend application
     */
    public function buildEntityManager(?array $dataLayerConfiguration = null) : Object
    {
        return (new ClientRepositoryFactory(
            $this->crudIdentifier,
            $this->tableSchema,
            $this->tableSchemaID))->buildEntityManager($dataLayerConfiguration);
    }
}
