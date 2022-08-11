<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Orm\QueryBuilder;

use Exception;

interface QueryBuilderInterface
{

    public function insertQuery() : string;
    public function selectQuery() : string;
    public function updateQuery() : string;
    public function deleteQuery() : string;

    public function searchQuery() : string;

    public function rawQuery() : string;

}
