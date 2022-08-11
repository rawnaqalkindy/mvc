<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only.
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Orm\QueryBuilder;


class QueryBuilderFactory
{
    public function __construct()
    { }

    public function __create(string $queryBuilderString) : QueryBuilderInterface
    {
        $queryBuilderObject = new $queryBuilderString();
        if (!$queryBuilderString instanceof QueryBuilderInterface) {
            throw new DataLayerUnexpectedValueException($queryBuilderString . ' is not a valid Query builder object.');
//            echo $queryBuilderString . ' is not a valid Query builder object.';
        }
        return $queryBuilderObject;
    }

    /**
     * Create the QueryBuilder object
     */
    public function create(string $queryBuilderString) : QueryBuilderInterface
    {
        $queryBuilderObject = new $queryBuilderString();
        if (!$queryBuilderObject instanceof QueryBuilderInterface) {
            throw new DataLayerUnexpectedValueException($queryBuilderString . ' is not a valid Query builder object.');
//            echo $queryBuilderString . ' is not a valid Query builder object.';
        }
        return $queryBuilderObject;
    }


}
