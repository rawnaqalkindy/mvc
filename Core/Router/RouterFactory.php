<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Router;

use Abc\ErrorHandler\ErrorHandler;
use Abc\Utility\Log;
use Exception;

class RouterFactory
{
    protected Object $routerObject;
    protected $url;
    protected ?object $request = null;

    public function __construct(?string $url = null)
    {
        $this->url = $_SERVER['QUERY_STRING'] ?? $_SERVER['REQUEST_URI'];
    }

    public function createRouterObject() : RouterInterface
    {
        $this->routerObject = new Router();
        if (!$this->routerObject instanceof RouterInterface) {
            ErrorHandler::exceptionHandler(new Exception('Invalid router object'));
            exit;
        }

        return $this->routerObject;
    }

    public function buildRoutes(array $definedRoutes = [])
    {
        if (empty($definedRoutes)) {
            ErrorHandler::exceptionHandler(new Exception('No routes defined'));
            exit;
        }
//        print_r($definedRoutes);
//        exit;
        $params = [];
        if (count($definedRoutes) > 0) {
            foreach ($definedRoutes as $route => $param) {
                if (isset($param['namespace']) && $param['namespace'] !='') {
                    $params = ['namespace' => $param['namespace']];
                } elseif (isset($param['controller']) && $param['controller'] !='') {
                    $params = ['controller' => $param['controller'], 'action' => $param['action']];
                }
                if (isset($route)) {
                    $this->routerObject->add($route, $params);
                }
            }    
        }
        /* Add dynamic routes based on regular expression */
        $this->routerObject->add('{controller}/{action}');
        /* Dispatch the routes */
        $this->routerObject->dispatch($this->url);

    }
}