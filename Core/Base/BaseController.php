<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended using it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Base;

use Abc\ErrorHandler\ErrorHandler;
use Abc\Utility\Log;
use Exception;

class BaseController
{
    protected Object $templateEngine;

    public function __construct()
    {
        $this->templateEngine = new BaseView();
    }

    public function view(string $template, array $context = [])
    {
        Log::write('Is the templating engine available?');
        $this->throwExceptionIfViewNull();
        Log::write('Yes it is. Adding template extension.');
        $template = $template . TEMPLATE_EXTENSION;

        Log::write('Rendering the template response');
        $this->templateEngine::render($template, $context);
    }

    public function template(string $template, array $context = [])
    {
        Log::write('Rendering a built-in template response');
        $this->templateEngine::template($template, $context);
    }

    private function throwExceptionIfViewNull(): void
    {
        if (null === $this->templateEngine) {
            ErrorHandler::exceptionHandler(new Exception('Nope. You can not use the render method if the build in template engine is not available'), CRITICAL_LOG);
            exit;
        }
    }

    public function _permissionDenied($unauthorized_task = null) {
        if ($unauthorized_task != null && $unauthorized_task != '') {
            ErrorHandler::exceptionHandler(new Exception('No permission to access: ' . $unauthorized_task), EXCEPTION_LOG, 403);
            exit;
        }
    }

    private function retrieveModuleName($module_class) {
        $temp_array = explode('\\', $module_class);
//        print_r($temp_array);
        return $temp_array[1];
    }
}
