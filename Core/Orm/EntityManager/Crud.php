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
use Abc\Orm\DataMapper\DataMapper;
use Abc\Orm\QueryBuilder\QueryBuilder;
use Abc\Utility\Log;
use Exception;
use Throwable;

class Crud implements CrudInterface
{
    protected DataMapper $dataMapper;
    protected QueryBuilder $queryBuilder;

    protected string $tableSchema;
    protected string $tableSchemaID;

    private string $createQuery;
    private string $readQuery;
    private string $joinQuery;
    private string $updateQuery;
    private string $deleteQuery;
    private string $searchQuery;
    private string $rawQuery;

    public function __construct(DataMapper $dataMapper, QueryBuilder $queryBuilder, string $tableSchema, string $tableSchemaID)
    {
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    public function getSchema(): string
    {
        return $this->tableSchema;
    }

    public function getMapping(): Object
    {
        return $this->dataMapper;
    }

    public function getSchemaID(): string
    {
        return $this->tableSchemaID;
    }

    public function lastID(): int
    {
        return $this->dataMapper->getLastId();
    }

    /**
     * Undocumented function
     */
    public function join(
        array $selectors,
        array $joinSelectors,
        string $joinTo,
        string $joinType,
        array $conditions = [],
        array $parameters = [],
        array $extras = []
    ): ?array
    {

        $args = ['table' => $this->getSchema(), 'type' => 'join', 'selectors' => $selectors, 'join_to_selectors' => $joinSelectors, 'join_to' => $joinTo, 'join_type' => $joinType, 'conditions' => $conditions, 'params' => $parameters, 'extras' => $extras];
        $query = $this->queryBuilder->buildQuery($args)->joinQuery();
        $this->joinQuery = $query;
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $parameters));
        return ($this->dataMapper->numRows() >= 1) ? $this->dataMapper->results() : NULL;
    }

    public function create(array $fields = []): bool
    {
        $args = ['table' => $this->getSchema(), 'type' => 'insert', 'fields' => $fields];
        $query = $this->queryBuilder->buildQuery($args)->insertQuery();
        $this->createQuery = $query;
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
        return $this->dataMapper->numRows() == 1;
    }

    public function read(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        $args = ['table' => $this->getSchema(), 'type' => 'select', 'selectors' => $selectors, 'conditions' => $conditions, 'params' => $parameters, 'extras' => $optional];
        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->readQuery = $query;
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $parameters));
        return ($this->dataMapper->numRows() >= 1) ? $this->dataMapper->results() : array();
    }

    public function update(array $fields, string $primaryKey): bool
    {
        $args = ['table' => $this->getSchema(), 'type' => 'update', 'fields' => $fields, 'primary_key' => $primaryKey];
        $query = $this->queryBuilder->buildQuery($args)->updateQuery();
        $this->updateQuery = $query;
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
        return $this->dataMapper->numRows() == 1;
    }

    public function delete(array $conditions = []): bool
    {
        $args = ['table' => $this->getSchema(), 'type' => 'delete', 'conditions' => $conditions];
        $query = $this->queryBuilder->buildQuery($args)->deleteQuery();
        $this->deleteQuery = $query;
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        return $this->dataMapper->numRows() == 1;
    }

    public function search(array $selectors = [], array $conditions = []): array
    {
        $args = ['table' => $this->getSchema(), 'type' => 'search', 'selectors' => $selectors, 'conditions' => $conditions];
        $query = $this->queryBuilder->buildQuery($args)->searchQuery();
        $this->searchQuery = $query;

        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        return ($this->dataMapper->numRows() >= 1) ? $this->dataMapper->results() : array();
    }

    public function get(array $selectors = [], array $conditions = []): ?Object
    {
        $args = ['table' => $this->getSchema(), 'type' => 'select', 'selectors' => $selectors, 'conditions' => $conditions];
        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->getQuery = $query;
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        return ($this->dataMapper->numRows() >= 1) ? $this->dataMapper->result() : NULL;
    }

    public function aggregate(string $type, ?string $field = 'id', array $conditions = [])
    {
        $args = [
            'table' => $this->getSchema(), 'primary_key' => $this->getSchemaID(),
            'type' => 'select', 'aggregate' => $type, 'aggregate_field' => $field,
            'conditions' => $conditions
        ];

        $query = $this->queryBuilder->buildQuery($args)->selectQuery();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        if ($this->dataMapper->numRows() > 0){
            return $this->dataMapper->column();
        }
        Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
        exit;
    }

    public function countRecords(array $conditions = [], ?string $field = 'id'): int
    {
        if ($this->getSchemaID() != '') {
            return empty($conditions) ? $this->aggregate('count', $this->getSchemaID()) : $this->aggregate('count', $this->getSchemaID(), $conditions);
        }
        Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
        exit;
    }

    public function rawQuery(string $rawQuery, ?array $conditions = [], string $resultType = 'column')
    {
        $args = ['table' => $this->getSchema(), 'type' => 'raw', 'conditions' => $conditions, 'raw' => $rawQuery];
        $query = $this->queryBuilder->buildQuery($args)->rawQuery();
        $this->rawQuery = $query;
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        if ($this->dataMapper->numRows()) {
            if (!in_array($resultType, ['fetch', 'fetch_all', 'column', 'columns'])) {
                ErrorHandler::exceptionHandler(new Exception('Invalid 3rd argument. Your options are "fetch, fetch_all or column"'));
                exit;
            }

            switch ($resultType) {
                case 'column':
                    return $this->dataMapper->column();
                    break;
                case 'columns':
                    return $this->dataMapper->columns();
                    break;
                case 'fetch':
                    return $this->dataMapper->result();
                    break;
                case 'fetch_all':
                    return $this->dataMapper->results();
                    break;
                default:
                    ErrorHandler::exceptionHandler(new Exception('Please choose a return type for this method ie. "fetch, fetch_all or column."'));
                    break;
            }
        }
        Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
        return false;
    }

    public function getQueryType(string $type)
    {
        $queryTypes = ['createQuery', 'readQuery', 'updateQuery', 'deleteQuery', 'joinQuery', 'searchQuery', 'rawQuery'];
        if (!empty($type)) {
            if (in_array($type, $queryTypes, true)) {
                return $this->$type;
            }
            Log::evo_log($type . ' is not recognized as a query type', ERROR_LOG);
        }
        Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
        exit;
    }

}
