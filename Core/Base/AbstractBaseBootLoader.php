<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare (strict_types = 1);

namespace Abc\Base;

use Abc\Container\ContainerFactory;
use Abc\ErrorHandler\ErrorHandler;
use Abc\Router\Router;
use Abc\Utility\Log;
use Abc\Router\RouterFactory;
use Abc\Utility\Stringify;
use Exception;

abstract class AbstractBaseBootLoader
{
    protected BaseApplication $application;
    protected Stringify $stringify;
    protected Router $router;
    protected array $routes = [];
    protected string $appPath;

    public function __construct(BaseApplication $application)
    {
        $this->application = $application;
        $this->stringify = new Stringify();
        $this->router = new Router();
        $this->routes = ROUTES;
        $this->appPath = ROOT_PATH;
    }

    public function app(): BaseApplication
    {
        return $this->application;
    }

    protected function defaultRouteHandler(): string
    {
        Log::evo_log('Checking QUERY_STRING or REQUEST_URI variable from $_SERVER');
        if (isset($_SERVER)) {
            Log::evo_log('We\'ve got something here...');
            return $_SERVER['QUERY_STRING'] ?? $_SERVER['REQUEST_URI'];
        }
        Log::evo_log('Neither QUERY_STRING nor REQUEST_URI variables were found. How weird is that?', 'alert');
        exit;
    }

    public function getRoutes(): array
    {
        Log::evo_log('Getting the routes');
        if (count($this->routes) < 0) {
            ErrorHandler::exceptionHandler(new Exception('No routes detected within your configuration file(s)'), CRITICAL_LOG);
            exit;
        }
        Log::evo_log('Routes have been found');
        return $this->routes;
    }

    public static function diGet(string $dependencies)
    {
//        echo $dependencies . '<br>';
//        exit;
        Log::evo_log('Getting classes to be loaded by the framework: ' . $dependencies);
        $container = (new ContainerFactory())->create();
//        print_r($container);echo '<br>';
//        exit;

        if ($container) {
            Log::evo_log('The container has been created. Returning the container...');
            return $container->get($dependencies);
        }

        Log::evo_log('The container hasn\'t been created', WARNING_LOG);
        exit;
    }
}