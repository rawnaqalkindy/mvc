<?php
namespace App\Models;

use Abc\Base\AbstractBaseModel;

 class EmployeeModel extends AbstractBaseModel {

    protected const tableSchema = 'employee';
    protected const tableSchemaId = 'id';

    public function __construct() {
        parent::__construct(self::tableSchema, self::tableSchemaId);
    }


    
}