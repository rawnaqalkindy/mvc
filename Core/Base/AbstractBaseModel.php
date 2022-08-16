<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended using it in production as it is.
 */

declare (strict_types = 1);

namespace Abc\Base;

use Abc\Utility\Log;

abstract class AbstractBaseModel extends BaseModel
{
    public function create()
    {
        Log::write('Saving a new object');
    }

    public function read()
    {
        Log::write('Retrieving object(s) from the database');
        $sql = "SELECT * FROM " . $this->tableSchema;

        $result = $this->db->query($sql);
        print_r($result);
        exit;
    }

    public function update($id)
    {
        Log::write('Updating the object using its ID');
    }

    public function delete($id)
    {
        Log::write('Deleting the object using its ID');
    }
}
