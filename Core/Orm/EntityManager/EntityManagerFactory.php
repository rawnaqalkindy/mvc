<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Orm\EntityManager;

use Abc\ErrorHandler\ErrorHandler;
use Abc\Orm\QueryBuilder\QueryBuilderInterface;
use Abc\Orm\DataMapper\DataMapperInterface;
use Exception;

class EntityManagerFactory
{
    protected DataMapperInterface $dataMapper;
    protected QueryBuilderInterface $queryBuilder;

    public function __construct(DataMapperInterface $dataMapper, QueryBuilderInterface $queryBuilder)
    {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Create the entityManager object and inject the dependency which is the crud object
     */
    public function create(string $crudString, string $tableSchema, string $tableSchemaID) : EntityManagerInterface
    {
        $crudObject = new $crudString($this->dataMapper, $this->queryBuilder, $tableSchema, $tableSchemaID);
        if (!$crudObject instanceof CrudInterface) {
            ErrorHandler::exceptionHandler(new Exception($crudString . ' is not a valid crud object.'));
            exit;
        }
        return new EntityManager($crudObject);
    }

}
