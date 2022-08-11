<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Base;

use Abc\Base\Middlewares\Error404;
use Abc\Base\Traits\ControllerCastingTrait;
use Abc\Base\Traits\ControllerMenuTrait;
use Abc\Base\Traits\ControllerPrivilegeTrait;
use Abc\Base\Traits\ControllerViewTrait;
use Abc\ErrorHandler\ErrorHandler;
use Abc\Utility\Log;
use Abc\Utility\Flash;
use Abc\Utility\Stringify;
use Exception;

class BaseController extends AbstractBaseController
{
    use ControllerCastingTrait;
    use ControllerPrivilegeTrait;
    use ControllerMenuTrait;
    use ControllerViewTrait;

    protected array $routeParams;

    protected Object $templateEngine;
    protected Flash $flashObject;

    protected array $callBeforeMiddlewares = [];
    protected array $callAfterMiddlewares = [];

    protected ?array $items;
    private ?array $__method;
    public array $error = [];
    protected array $all_relationships = [];



    public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);

        $this->routeParams = $routeParams;
        $this->templateEngine = new BaseView();
        $this->flashObject = new Flash;
    }

    /**
     * Magic method called when a non-existent or inaccessible method is
     * called on an object of this class. Action methods need to be named
     * with an "Action" suffix, e.g. indexAction, showAction etc.
     */
    public function __call(string $name, array $args)
    {
//        Log::evo_log('Non-existent method: ' . $name . '. Appending \'Action\' suffix');
//        $method = $name . 'Action';
//
        if (method_exists($this, $name)) {
//            Log::evo_log('Method ' . $method . ' exists');
//            if ($this->before() !== false) {
                call_user_func_array([$this, $name], $args);
//                $this->after();
//            }
        } else {
            ErrorHandler::exceptionHandler(new Exception($name . ' not found in ' . get_class($this)), CRITICAL_LOG, 404);
            exit;
        }
    }

    /**
     * Render a template response using Twig templating engine
     */
    public function view(string $template, array $context = [])
    {
        Log::evo_log('Is the templating engine available?');
        $this->throwExceptionIfViewNull();
        Log::evo_log('Yes it is. Adding template extension.');
        $template = $template . TEMPLATE_EXTENSION;
//        echo $template;

        Log::evo_log('Rendering the template response');
//        $this->templateEngine::renderTemplate($template, $context); // OG
        $this->templateEngine::render($this->retrieveModuleName(get_called_class()), $template, $context);
    }

    public function template(string $template, array $context = []) // OG
    {
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
