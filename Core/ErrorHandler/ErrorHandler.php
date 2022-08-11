<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\ErrorHandler;

use ErrorException;
use Abc\Utility\Log;

class ErrorHandler
{

    /**
     * Number of lines to be returned
     */
    private const NUM_LINES = 10;
    private static array $trace = [];

    private const ERROR_CODES = [404, 403];
    private const DEFAULT_ERROR_CODE = 500;

    public function __construct()
    {
        register_shutdown_function(function(){
            $error = error_get_last();
            if($error){
                $message = "Error [" . $error['type'] . "]: ";
                $message .= "Message: " . $error['message'] . " - ";
                $message .= "File: " . $error['file'] . " - ";
                $message .= "Line: " . $error['line'];

                Log::evo_log($message, ERROR_LOG, new ErrorException($error['message'], -1, $error['type'], $error['file'], $error['line']));
            }
        });
    }

    public static function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $message = "Code: " . $errno . "\n";
        $message .= "Message: " . $errstr . "\n";
        $message .= "File: " . $errfile . "\n";
        $message .= "Line: " . $errline;

        $data = 'An error was thrown: ' . $errfile . ' @ ' . $errline;

        Log::evo_log($data, 'error', $message);

//        $request_number = Log::$request_number;

//        require_once TEMPLATE_PATH . 'errors/500.php';
//        exit;
    }

    public static function exceptionHandler($exception, $log_type = EXCEPTION_LOG, $code = null)
    {
        if (!in_array($code, self::ERROR_CODES)) {
            $code = self::DEFAULT_ERROR_CODE;
        }

        $code = ($code == null) || ($code == '') ? 500 : $code;

        http_response_code($code);

        $message = "Uncaught exception: \n" . get_class($exception);
        $message .= "\nMessage: " . $exception->getMessage();
        $message .= "\nStack trace:\n" . $exception->getTraceAsString();
        $message .= "\nFile: " . $exception->getFile();
        $message .= "\nLine: " . $exception->getLine();

        Log::evo_log('An exception was thrown: ' . $exception->getFile() . ' @ ' . $exception->getLine(), $log_type, $message);

        $content = self::errorContent($code, Log::$request_number);

        require_once TEMPLATE_PATH . 'error.php';
//        exit;
    }

    private static function errorContent($code, $request_number): array
    {
        switch ($code) {
            case 404:
                return [
                    'title' => 'Page NOT Found',
                    'header' => 'Are you lost?',
                    'message' => 'PAGE NOT FOUND',
                    'extra_info' => 'We can\'t seem to find the page you\'re looking for',
                ];
            case 403:
                return [
                    'title' => 'Forbidden',
                    'header' => 'Unauthorized Access',
                    'message' => MSG_403,
                    'extra_info' => 'You are not allowed to access this content',
                ];
            default:
                return [
                    'title' => 'Fatal Error',
                    'header' => 'Something is not right...',
                    'message' => 'INTERNAL SERVER ERROR',
                    'extra_info' => 'Something went wrong. Contact your Administrator with the Request ID: ' . $request_number
                ];
        }
    }
}
