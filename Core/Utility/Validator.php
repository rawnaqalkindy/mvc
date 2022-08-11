<?php

declare(strict_types = 1);

namespace Abc\Utility;

class Validator
{
    public static function email(string $email)
    {
        if (!empty($email)) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }
    }
}