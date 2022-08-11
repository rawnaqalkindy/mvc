<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Container;

/** PSR-11 Container */
interface ContainerServicesInterface
{

    /**
     * Set Class services
     */
    public function setServices(array $services = []): self;

    /**
     * Get class service or services
     */
    public function getServices(): array;

    /**
     * Unregister a service from being instantiable
     */
    public function unregister(array $args = []): self;

    /**
     * Register service or services with auto-wiring
     */
    public function register();
}
