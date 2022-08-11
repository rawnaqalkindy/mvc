<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare (strict_types = 1);

namespace Abc\Orm\ClientRepository;

use Abc\ErrorHandler\ErrorHandler;
use Exception;
use Abc\Utility\Stringify;
use Abc\Base\BaseApplication;

trait ClientRelationshipTrait
{

    public function findManyToMany(string $tablePivot)
    {
        if ($tablePivot) {
            $newPivotObject = BaseApplication::diGet($tablePivot);
            if (!$newPivotObject) {
                ErrorHandler::exceptionHandler(new Exception());
                exit;
//                throw new Exception();
            }
            /* explode the pivot table string and extract both associative tables */
            $tableNames = explode('_', $newPivotObject->getSchema());
            if (is_array($tableNames) && count($tableNames) > 0) {
                $test = array_filter($tableNames, function($tableName) {
                    $suffix = 'Model';
                    $namespace = '\App\Model\\';
        
                    if (is_string($tableName)) {
                        $modelNameSuffix = $tableName . $suffix;
                        $modelName = Stringify::convertToStudlyCaps($modelNameSuffix);
                        if (class_exists($newModelClass = $namespace . $modelName)) {
                            $newModelObject = BaseApplication::diGet($newModelClass);
                            if (!$newModelObject) {
                                ErrorHandler::exceptionHandler(new Exception($newModelClass . ' not found'));
                                exit;
                            }

                        }
                        return $newModelObject;

                    }
                });
                var_dump($test);
                die;
            }
        }
    }

}