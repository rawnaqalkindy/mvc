<?php

namespace Abc\Base\Traits;

use Abc\ErrorHandler\ErrorHandler;
use Abc\Utility\Log;
use Exception;

trait ControllerViewTrait
{
    private function throwExceptionIfViewNull(): void
    {
//        //Log::evo_log('Checking if view is null');
        if (null === $this->templateEngine) {
            ErrorHandler::exceptionHandler(new Exception('You can not use the render method if the build in template engine is not available'), CRITICAL_LOG);
            exit;
        }
    }

//    /**
//     * Render a template response using Twig templating engine
//     */
//    public function view(string $template, array $context = [])
//    {
////        //Log::evo_log('Checking if view is null');
//        $this->throwExceptionIfViewNull();
//        $template = $template . '';
//
////        //Log::evo_log('Rendering a template response');
////        $this->templateEngine::renderTemplate($this->__method[1], $template, $context); // NEW ATTEMPT
//        $this->templateEngine::renderTemplate($template, $context);
//    }
//
//    public function render(string $template, array $context = [])
//    {
////        //Log::evo_log('Rendering the view');
//        $this->view($template, $context);
//    }
}
