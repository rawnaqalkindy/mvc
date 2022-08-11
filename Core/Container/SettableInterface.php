<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only.
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Container;

use Closure;

/** PSR-11 Container */
interface SettableInterface
{

    /**
     * Explicitly set one or more dependency. Dependencies are auto set when
     * using the get() method to fetch an unset dependency
     */
    public function set(string $id, Closure $concrete = null): void;
}
