<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Base\Traits;

trait BootstrapTrait
{
    /**
     * Default settings uses a common basic structure which defines the parameters
     * for components within this framework which exposes configurable
     * parameters. This little snippet helps us to load the default settings which
     * can be overridden by the use app config yaml files
     */
    private function getDefaultSettings(array $config)
    {
        if (count($config) > 0) {
            if (array_key_exists('drivers', $config)) {
                $defaultDriver  = $config['default_driver'];
                if (array_key_exists($defaultDriver, $config['drivers'])) {
                    $selectedDriver = $config['drivers'][$defaultDriver];
                    if ($selectedDriver['default'] === true) {
                        return $selectedDriver['class'];
                    }
                }
            }
        }
    }


}
