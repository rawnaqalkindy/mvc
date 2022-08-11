<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Router;

use Closure;

interface RouterInterface
{
    /**
     * Add a route to the routing table
     */
    public function add(string $route, array $params = []);

    /**
     * Dispatch the route, creating the controller object and running the
     * action method
     */
    public function dispatch(string $url);

    /**
     * Get the currently matched parameters
     *
     */
    public function getParams() : array;

    /**
     * Get all the routes from the routing table
     */
    public function getRoutes() : array;
}