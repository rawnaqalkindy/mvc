<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Base\Traits;

use Abc\Base\BaseApplication;
use Abc\ErrorHandler\ErrorHandler;
use Abc\Utility\Log;
use Exception;

trait ControllerTrait
{
    /**
     * Method for allowing child controller class to dependency inject other objects
     */
    protected function diContainer(?array $args = null)
    {
//        echo __METHOD__ . '<br>';
//        exit;
//        //Log::evo_log('Child controller class dependency injecting other objects');

        if ($args !== null && !is_array($args)) {
            ErrorHandler::exceptionHandler(new Exception('Invalid argument called in container. Your dependencies should return a key/value pair array'), CRITICAL_LOG);
            exit;
        }
//        echo __METHOD__ . '<br>';
//        exit;

        $args = func_get_args();
//        echo __METHOD__ . '<br>';
//        exit;

        if ($args) {
//            //Log::evo_log('Arguments are available');
//            echo __METHOD__ . '<br>';
//            exit;
            $output = '';
            foreach ($args as $arg) {
//                print_r($arg);
                foreach ($arg as $property => $class) {
//                    echo 'Property: ';print_r($property);echo '<br>';
//                    echo 'Class: ';print_r($class);echo '<br>';
                    if ($class) {
                        $output = ($property === 'dataColumns' || $property === 'column') ? $this->$property = $class : $this->$property = BaseApplication::diGet($class);
//                        $output = ($property === 'dataColumns' || $property === 'column') ? 'true' : 'NOT true';
                    }
                }
            }
//            //Log::evo_log('Returning output');
//            echo __METHOD__ . '<br>';
//            echo $output;
//            exit;
            return $output;
        }
        Log::evo_log('Arguments not found', ERROR_LOG);
    }

    /**
     * Alias of diContainer
     */
    public function addDefinitions(?array $args = null)
    {
        return $this->diContainer($args);
    }

}
