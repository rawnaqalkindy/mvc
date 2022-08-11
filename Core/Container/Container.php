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
use Exception;
use ReflectionClass;
use Closure;
use Abc\Utility\Log;
use ReflectionException;
use ReflectionParameter;

/** PSR-11 Container */
class Container implements ContainerInterface, SettableInterface
{

    protected array $instance = [];
    protected array $services = [];
    protected object $service;
    protected array $unregister = [];

    public function set(string $id, Closure $concrete = null): void
    {
        if ($concrete === null) {
            $concrete = $id;
        }
        $this->instance[$id] = $concrete;
    }

    public function get(string $id)
    {
        if (!$this->has($id)) {
            $this->set($id);
        }
        $concrete = $this->instance[$id];
        return $this->resolved($concrete);
    }

    public function has(string $id): bool
    {
        return isset($this->instance[$id]);
    }

    /**
     * Resolves a single dependency
     */
    protected function resolved(string $concrete)
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        $reflection = new ReflectionClass($concrete);
        /* Check to see whether the class is instantiable */
        if (!$reflection->isInstantiable()) {
            ErrorHandler::exceptionHandler(new Exception('Class ' . $concrete . ' is not instantiable'), CRITICAL_LOG);
            exit;
        }

        /* Get the class constructor */
        $constructor = $reflection->getConstructor();
        if (is_null($constructor)) {
            /* Return the new instance */
            return $reflection->newInstance();
        }

        /* Get the constructor parameters */
        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters, $reflection);
        /* Get the new instance with dependency resolved */
        return $reflection->newInstanceArgs($dependencies);
    }

    /**
     * Resolves all the dependencies
     */
    protected function getDependencies($parameters, ReflectionClass $reflection): array
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getType() && !$parameter->getType()->isBuiltin()
                ? new ReflectionClass($parameter->getType()->getName())
                : null;
            if (is_null($dependency)) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    ErrorHandler::exceptionHandler(new Exception('Sorry cannot resolve class dependency ' . $parameter->name), CRITICAL_LOG);
                    exit;
                }
            } elseif (!$reflection->isUserDefined()) {
                $this->set($dependency->name);
            } else {
                $dependencies[] = $this->get($dependency->name);
            }
        }

        return $dependencies;
    }

    public function SetServices(array $services = []): self
    {
        if ($services)
            $this->services = $services;

        return $this;
    }

    public function getServices(): array
    {
        return $this->services;
    }

    public function unregister(array $args = []): self
    {
        $this->unregister = $args;
        return $this;
    }
}
