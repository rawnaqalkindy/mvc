<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended using it in production as it is.
 */

declare(strict_types = 1);

/**
 * Load the composer autoloader library which enables us to bootstrap the application
 * and initialize the necessary components.
 */

use Abc\Base\BaseApplication;
use Abc\Utility\Log;

require_once 'include.php';
//print_r(phpversion());
//exit;
//echo APP['error_handler'] . '<br>';
//echo APP['exception_handler'] . '<br>';

error_reporting(E_ALL);
set_error_handler(APP['error_handler']);
set_exception_handler(APP['exception_handler']);

session_start();

function getRequestData(): array
{
    $data = [];
    $files = [];

    if ($_FILES) {
        $files = $_FILES;
        $data = $_POST;
    } else {
        $data = file_get_contents('php://input');
    }

    return [
        'data' => $data, 'files' => $files
    ];
}

Log::$request_number = hrtime(true);

Log::evo_log("*********************************************************************************************************************************\n\n", 'plain');
Log::evo_log('REQUEST-STARTED');
$url = $_SERVER['QUERY_STRING'] ?? $_SERVER['REQUEST_URI'];

if (strpos($url, '.ico') || strpos($url, '.png') || strpos($url, '.jpg')) {
    Log::evo_log('Invalid URL: ' . $url);
    Log::evo_log('REQUEST-TERMINATED');
    exit;
}

Log::evo_log('REQUEST-URL: ' . $url);
Log::evo_log('REQUEST-CONTENT-TYPE: ' . (array_key_exists('CONTENT_TYPE', $_SERVER) ? $_SERVER['CONTENT_TYPE'] : null));
Log::evo_log('REQUEST-DATA: ' . json_encode(getRequestData()));

try {
    new BaseApplication();
} catch (Exception $e) {
    echo $e->getMessage();
}

Log::evo_log('REQUEST-ENDED');
