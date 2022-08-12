<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended using it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Base;

use Abc\ErrorHandler\ErrorHandler;
use Abc\Utility\Log;
use Exception;

class BaseApplication
{
    protected string $controller = 'home';
    protected string $controller_suffix = 'controller';
    protected string $method = 'index';

    public function __construct()
    {
//        echo '<pre>';
//        parent::__construct($this);

        $this->run();
    }

    private function run()
    {
        $url = $this->parseUrl() ?? [];
//        echo 'URL: ';
//        print_r($url);

        $controller = !empty($url) ? ucfirst(strtolower($url[0])) : ucfirst(strtolower($this->controller));
        $controller_with_namespace = 'App\\Controllers\\' . $controller . ucfirst(strtolower($this->controller_suffix));
        Log::evo_log('Controller: ' . $controller);
        Log::evo_log('Controller With Namespace: ' . $controller_with_namespace);

        if (!class_exists($controller_with_namespace)) {
            ErrorHandler::exceptionHandler(new Exception('Class ' . $controller . ' does not exist'), CRITICAL_LOG, 404);
            exit;
        }
        Log::evo_log('Controller exists');
        $controller_object = new $controller_with_namespace;
//        print_r($controller_object);
        Log::evo_log('Controller object created. Searching for the method to be called...');

        $method = isset($url[1]) ? strtolower($url[1]) : strtolower($this->method);

        Log::evo_log('Method: ' . $method);

        Log::evo_log('Is ' . $controller . '@' . $method . ' callable?');

        if (is_callable([$controller_object, $method])) {
            Log::evo_log('Yes. Calling it...');
            $controller_object->$method();
        } else {
            ErrorHandler::exceptionHandler(new Exception('Method ' . $method . ' NOT FOUND in controller ' . $controller), CRITICAL_LOG, 404);
            exit;
        }
    }

    protected function parseUrl()
    {
        Log::evo_log('Parsing the URL');

        if (isset($_SERVER)) {
            $unclean_url = $_SERVER['QUERY_STRING'] ?? $_SERVER['REQUEST_URI'];

            if ($unclean_url) {
                return explode('/', filter_var(rtrim($unclean_url, '/'), FILTER_SANITIZE_URL));
            }
        } else {
            Log::evo_log('The super global $_SERVER variable was NOT found. How weird is that?', 'alert');
            return null;
        }
    }
}