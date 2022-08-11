<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Container;

use Abc\ErrorHandler\ErrorHandler;
use Abc\Utility\Log;
use Exception;

/** PSR-11 Container */
class ContainerFactory
{
    protected array $providers = [];

    public function __construct()
    {
    }

    /**
     * Factory method which creates the container object.
     */
    public function create(?string $container = null): ContainerInterface
    {
//        Log::evo_log('Creating a Container instance');
        $containerObject = ($container != null) ? new $container() : new Container();

        if (!$containerObject instanceof ContainerInterface) {
//            echo 'NOT a valid container<br>';
            ErrorHandler::exceptionHandler(new Exception($container . ' is not a valid container object'), CRITICAL_LOG);
            exit;
        }
//        echo 'Valid container<br>';
        Log::evo_log('Returning a Container object');
        return $containerObject;
    }
}
