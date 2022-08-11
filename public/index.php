<?php
/*
 * This file is part of the evo package.
 *
 * (c) John Andrew <simplygenius78@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

error_reporting(E_ALL);
set_error_handler(APP['error_handler']['error']);
set_exception_handler(APP['error_handler']['exception']);

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

Log::sysLog('Request initiated.');

Log::evo_log("*********************************************************************************************************************************\n\n", 'plain');
Log::evo_log('REQUEST-STARTED');
$url = $_SERVER['QUERY_STRING'] ?? $_SERVER['REQUEST_URI'];

if (strpos($url, '.ico') || strpos($url, '.png') || strpos($url, '.jpg')) {
    Log::evo_log('Invalid URL: ' . $url);
    Log::evo_log('REQUEST-TERMINATED');
    Log::sysLog('Request terminated.' . "\n");
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

Log::sysLog('Request completed lifecycle.' . "\n");
