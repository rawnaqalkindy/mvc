<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Base;

class AbstractBaseController
{
    protected array $routeParams;

    public function __construct(array $routeParams)
    {
        if ($routeParams)
            $this->routeParams = $routeParams;
    }

    public function getCurrentControllerAsString(): string
    {
//        //Log::evo_log('Returning the current controller name');
        return isset($this->routeParams['controller']) ? strtolower($this->routeParams['controller']) : '';
    }

    public function getCurrentActionAsString(): string
    {
//        //Log::evo_log('Returning the current controller action');
        return isset($this->routeParams['action']) ? strtolower($this->routeParams['action']) : '';
    }

    public function getCurrentNamespaceAsString(): string
    {
//        //Log::evo_log('Returning the current controller namespace');
        return isset($this->routeParams['namespace']) ? strtolower($this->routeParams['namespace']) : '';
    }

    /**
     * Return the current controller token as a string
     */
    public function getCurrentTokenAsString(): ?string
    {
//        //Log::evo_log('Setting the current controller token');
        $token = $this->routeParams['token'] ?? null;
//        //Log::evo_log('Returning the route token');
        return (string)$token;
    }

    /**
     * Return the current controller route ID if set as an int
     */
    public function thisRouteID(): int
    {
//        //Log::evo_log('Setting the current controller route ID');
        $ID = $this->routeParams['id'] ?? false;
//        //Log::evo_log('Returning ID');
        return intval($ID);
    }

    public function convertToArray(Object $data): array
    {
//        //Log::evo_log('Converting object to array');
        return (array)$data;
    }
}