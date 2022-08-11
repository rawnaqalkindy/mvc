<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Base;

use Abc\ErrorHandler\ErrorHandler;
use Abc\Router\RouterFactory;
use Abc\Utility\Log;
use Exception;

class BaseApplication /*extends AbstractBaseBootLoader*/
{
    protected string $controller = 'home';
    protected string $method = 'index';

    protected array $params = [];

    public function __construct()
    {
//        parent::__construct($this);

        $this->run();
    }

    private function run()
    {
        $url = $this->parseUrl();

        $controller = $url ? ucfirst(strtolower($url[0])) : ucfirst(strtolower($this->controller));
        $controller_with_namespace = 'App\\Controllers\\' . $controller;

        if (file_exists(ROOT_PATH . '/App/Controllers/' . $controller . '.php')) {
            $this->controller = $controller;
            unset($url[0]);
        }

//        echo ROOT_PATH . '/App/Controllers/' . $controller . '.php';
        require_once ROOT_PATH . '/App/Controllers/' . $controller . '.php';

        echo $controller_with_namespace;
        $this->controller = new $controller;
//        $this->controller = new $controller_with_namespace;
//
//        if (isset($url[1])) {
//            if (method_exists($this->controller, $url[1])) {
//                $this->method = $url[1];
//                unset($url[1]);
//            }
//        } else {
//            ErrorHandler::exceptionHandler(new Exception($url[1] . ' not found in ' . get_class($this)), CRITICAL_LOG, 404);
//            exit;
//        }
//
//        $this->params = $url ? array_values($url) : [];
//
//        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl()
    {
        $unclean_url = $_SERVER['QUERY_STRING'] ?? $_SERVER['REQUEST_URI'];

        if ($unclean_url) {
//            echo $_SERVER['QUERY_STRING'] . '<br>';
            return explode('/', filter_var(rtrim($unclean_url, '/'), FILTER_SANITIZE_URL));
        }
    }
}