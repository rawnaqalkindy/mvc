<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended using it in production as it is.
 */

namespace Abc\Base;

use Abc\ErrorHandler\ErrorHandler;
use Exception;

class BaseView
{
    public static function render(string $template, array $optional_view_data = [])
    {
        $file = ROOT_PATH . '/App/Views/' . $template;
//        echo $file;

        if (is_readable($file)) {
            require_once $file;
        } else {
            ErrorHandler::exceptionHandler(new Exception("$file not found"));
            exit;
        }
    }

    public static function template(string $template, array $optional_view_data = []): string
    {
        $file = ROOT_PATH . '/App/Views/_templates/' . $template;
//        echo $file;

        if (is_readable($file)) {
            require $file;
        } else {
            ErrorHandler::exceptionHandler(new Exception("$file not found"));
            exit;
        }
    }
}
