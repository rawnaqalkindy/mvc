<?php

namespace Abc\Utility;

use Abc\Utility\Yaml;

trait UtilityTrait
{
    public function security(string $key)
    {
        return Yaml::file('app')['security'][$key];
    }

    public static function appSecurity(string $key)
    {
        return Yaml::file('app')['security'][$key];
    }
}