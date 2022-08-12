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
        Log::evo_log('Saving a new object');
    }

    public function read()
    {
        Log::evo_log('Retrieving object(s) from the database');
    }

    public function update($id)
    {
        Log::evo_log('Updating the object using its ID');
    }

    public function delete($id)
    {
        Log::evo_log('Deleting the object using its ID');
    }
}
