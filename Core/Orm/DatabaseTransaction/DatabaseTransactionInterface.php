<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare (strict_types = 1);

namespace Abc\Orm\DatabaseTransaction;

use PDOException;

interface DatabaseTransactionInterface
{

    /**
     * Begin a transaction, turning off autocommit
     */
    public function start() : bool;

    /**
     * Commits a transaction
     */
    public function commit () : bool;

    /**
     * Rolls back a transaction
     */
    public function revert() : bool;

}