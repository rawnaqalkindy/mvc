<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended using it in production as it is.
 */

declare (strict_types = 1);

namespace Abc\Base;

use Abc\ErrorHandler\ErrorHandler;
use Abc\Utility\Log;
use Exception;

class BaseModel
{
    protected string $tableSchema;
    protected string $tableSchemaID;

    public function __construct(string $tableSchema = null, string $tableSchemaID = null)
    {
        $this->throwExceptionIfEmpty($tableSchema, $tableSchemaID);

        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    private function throwExceptionIfEmpty(string $tableSchema, string $tableSchemaID): void
    {
        if (empty($tableSchema) || empty($tableSchemaID)) {
            ErrorHandler::exceptionHandler(new Exception('Your model is missing the required constants. Please pass the TABLESCHEMA and TABLESCHEMAID constants.'));
            exit;
        }
    }

    public function getSchemaID(): string
    {
        return $this->tableSchemaID;
    }

    public function getSchema(): string
    {
        return $this->tableSchema;
    }

}