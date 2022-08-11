<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare (strict_types = 1);

namespace Abc\Orm\DataMapper;

use Throwable;

interface DataMapperInterface
{

    /**
     * Prepare the query string
     */
    public function prepare(string $sqlQuery) : self;

    /**
     * Explicit dat type for the parameter using the PDO::PARAM_* constants.
     */
    public function bind($value): int;

    /**
     * Combination method which combines both methods above. One of which  is
     * optimized for binding search queries. Once the second argument $type
     * is set to search
     */
    public function bindParameters(array $fields, bool $isSearch = false) : self;

    /**
     * returns the number of rows affected by a DELETE, INSERT, or UPDATE statement.
     */
    public function numRows() : int;

    /**
     * Execute function which will execute the prepared statement
     */
    public function execute();

    /**
     * Returns a single database row as an object
     */
    public function result() : Object;

    /**
     * Returns all the rows within the database as an array
     */
    public function results() : array;

    /**
     * Returns a database column
     */
    public function column();

    /**
     * Returns the last inserted row ID from database table
     */
    public function getLastId() : int;


}
