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
use Abc\Utility\Stringify;

class Router implements RouterInterface
{
    /** Associative array of routes (the routing table) */
    protected array $routes = [];
    /** Parameters from the matched route */
    protected array $params = [];
    protected string $controllerSuffix = "Controller";
    private string $actionSuffix = 'Action';
    protected string $namespace = 'App\Controller\\';

    /**
     * Add a route to the routing table
     */
    // see if it's possible to set 'index' as the default method if a URL is passed with just the controller name
    public function add(string $route, array $params = [])
    {
//        //Log::evo_log('Performing the necessary route clean up');
        // Convert the route to a regular expression: escape forward slashes
//        //Log::evo_log('Convert the route to a regular expression: escape forward slashes');
        $route = preg_replace('/\//', '\\/', $route);

        // Convert variables e.g. {controller}
//        //Log::evo_log('Convert variables e.g. {controller}');
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

        // Convert variables with custom regular expressions e.g. {id:\d+}
//        //Log::evo_log('Convert variables with custom regular expressions e.g. {id:\d+}');
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        // Add start and end delimiters, and case-insensitive flag
//        //Log::evo_log('Add start and end delimiters, and case-insensitive flag');
        $route = '/^' . $route . '$/i';

//        //Log::evo_log('Setting the route');
        $this->routes[$route] = $params;
//        //Log::evo_log('Done setting route');
    }

    /**
     * Create the controller object name using the parameters defined within
     * the yaml configuration file. Route parameters are accessible using
     * the $this->params property and can fetch any key defined. ie
     * `controller, action, namespace, id etc...`
     */
    private function createController(): string
    {
//        Log::evo_log('Creating the controller name');
        $controllerName = $this->params['controller'] . $this->controllerSuffix;
//        Log::evo_log('Converting the controller name to StudlyCaps');
        $controllerName = Stringify::convertToStudlyCaps($controllerName);
//        Log::evo_log('Getting the namespace');
        $module_directory = Stringify::convertToStudlyCaps(str_replace($this->controllerSuffix, '', $controllerName));

        Log::evo_log('Namespace resolved: ' . $this->getNamespace() . $module_directory . '\\' . $controllerName);
        return $this->getNamespace() . $module_directory . '\\' . $controllerName;
    }

    /**
     * Create a camel case method name for the controllers
     */
    public function createAction(): string
    {
        Log::evo_log('Setting action to ' . $this->params['action']);
        $action = $this->params['action']; // ?? 'index';
//        Log::evo_log('Converting action ' . $action . ' to camelCase');
        return Stringify::convertToCamelCase($action);
    }

    /**
     * Match the route to the routes in the routing table, setting the $params
     * property if a route is found.
     */
    public function isRouteInRoutingTable(string $url): bool
    {
//        Log::evo_log('Looping through routing table');
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
//                echo 'match found';
                // Get named capture role values
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
//                Log::evo_log('URL ' . $url . ' found in routing table');
                $this->params = $params;
                return true;
            }
        }
        Log::evo_log('URL ' . $url . ' is not in routing table', 'error');
        return false;
    }

    /**
     * Check for validity within the url. If invalid we will throw an exception. if valid
     * we will then check the requested controller exists if not then throw another
     * exception. else return the controller as an array
     */
    private function dispatchWithException(string $url): array
    {
//        Log::evo_log('Removing query variables');
        $url = $this->removeQueryStringVariables($url);
//        echo (Stringify::convertToStudlyCaps(($url)));
//        Log::evo_log('Converting the URL to StudlyCaps');
        $url = (Stringify::convertToStudlyCaps(($url))); // for cashflow_status

        Log::evo_log('Checking if the URL ' . $url . ' is in the routing table');
        if (!$this->isRouteInRoutingTable($url)) {
            http_response_code(404);
            ErrorHandler::exceptionHandler(new Exception('Route ' . $url . ' does not match any valid route'), CRITICAL_LOG, 404);
            exit;
        }
//        echo 'URL: ' . $url . '<br>';
//        print_r($this->params);
//        exit;
//        Log::evo_log('Checking if the controller class exists');
        if (!class_exists($controller = $this->createController())) {
            ErrorHandler::exceptionHandler(new Exception('Class ' . $controller . ' does not exist'), CRITICAL_LOG, 404);
            exit;
        }
//        Log::evo_log('Controller class exists');
        return [$controller];
    }

    /**
     * Dispatch the route, creating the controller object and running the
     * action method
     */
    public function dispatch(string $url)
    {
        list($controller) = $this->dispatchWithException($url);

//        Log::evo_log('Creating a new object of ' . $controller);
        $controller_object = new $controller($this->params);

//        Log::evo_log('Creating the action');
        $action = $this->createAction();

        Log::evo_log('Is ' . $controller . '@' . $action . ' callable?');
        if (is_callable([$controller_object, $action])) {
            Log::evo_log('Yes. Calling it...');
            $controller_object->$action();
        } else {
            ErrorHandler::exceptionHandler(new Exception('Method ' . $action . '(in controller ' . $controller . ') not found'), CRITICAL_LOG, 404);
            exit;
        }
    }

    /**
     * Remove the query string variables from the URL (if any). As the full
     * query string is used for the route, any variables at the end will need
     * to be removed before the route is matched to the routing table. For
     * example:
     *
     *
     * A URL of the format localhost/?page (one variable name, no value) won't
     * work, however. (NB. The .htaccess file converts the first ? to a & when
     * it's passed through to the $_SERVER variable).
     */
    protected function removeQueryStringVariables(string $url): string
    {
        if ($url != '') {
//            Log::evo_log('The URL is not empty. Exploding the URL');
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false) {
                Log::evo_log('Setting URL to ' . $parts[0]);
                $url = $parts[0];
            } else {
//                Log::evo_log('Setting URL to an empty string');
                $url = '';
            }
        } else {
            Log::evo_log('The URL is empty', 'warning');
        }

//        Log::evo_log('Returning the URL');
        return $url;
    }

    /**
     * Get the namespace for the controller class. The namespace defined in the
     * route parameters is added if present.
     */
    protected function getNamespace(): string
    {
//        Log::evo_log('Setting the namespace to default');
        $namespace = '_Modules\\';

        if (array_key_exists('namespace', $this->params)) {
//            Log::evo_log('Overriding the default namespace');
            $namespace .= ucfirst(strtolower($this->params['namespace'])) . '\\';
        }/* else {
            $namespace .= 'App\\';
        }*/

//        Log::evo_log('Returning the namespace: ' . $namespace);
        return $namespace;
    }

    /**
     * Get all the routes from the routing table
     */
    public function getRoutes(): array
    {
//        Log::evo_log('Returning routes');
        return $this->routes;
    }

    /**
     * Get the currently matched parameters
     */
    public function getParams(): array
    {
//        Log::evo_log('Returning parameters');
        return $this->params;
    }

}
