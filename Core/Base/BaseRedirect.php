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
use Abc\Utility\Log;
use Exception;

class BaseRedirect
{
    protected string $url;

    /**
     * Optional. Indicates whether the header should replace a previous similar header or add a new  *
     * header of the same type. Default is TRUE (will replace). FALSE allows multiple headers of the *
     * same type
     */
    protected bool $replace = true;

    protected int $responseCode = 303;
    protected array $routeParams = [];

    protected const ROUTE_PARAMS = ['namespace', 'controller', 'action', 'id', 'token'];

    public function __construct(string $url, array $routeParams, bool $replace, int $responseCode)
    {
        if (empty($url)) {
            ErrorHandler::exceptionHandler(new Exception('Invalid header. This argument is required.'), CRITICAL_LOG);
            exit;
        }
        $this->url = $url;
        $this->replace = $replace;
        $this->responseCode = $responseCode;
        $this->routeParams = $routeParams;
    }

    public function validateRouteUrl() : bool
    {   
        $parts = explode('/', $this->url);
        if (count($parts) > 0) {
            foreach (self::ROUTE_PARAMS as $route) {
                if (isset($this->routeParams[$route]) && $this->routeParams[$route] !='') {
                    if (!in_array(strtolower($this->routeParams[$route]), array_filter($parts))) {
                        ErrorHandler::exceptionHandler(new Exception('The controller redirect method is returning an invalid url'), CRITICAL_LOG);
                        exit;
                    }
                }
            }
            return true;
        }
        return false;
    }

    public function redirect() : void
    {
//        $this->validateRouteUrl();
        if (!headers_sent()) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $this->url, $this->replace, $this->responseCode);
            exit;
        }
    }

}
