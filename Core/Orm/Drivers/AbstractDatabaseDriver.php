<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Orm\Drivers;

use Abc\Orm\Drivers\DatabaseDriverInterface;
use PDO;

abstract class AbstractDatabaseDriver implements DatabaseDriverInterface
{
    protected array $params = [
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    private ?object $dbh;

    public function PdoParams(): array
    {
        return $this->params;
    }

    public function close()
    {
        $this->dbh = null;
    }
}