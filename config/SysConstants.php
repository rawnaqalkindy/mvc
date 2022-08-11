<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only.
 * It is not recommended to use it in production as it is.
 */

$vendorPath = '/Core/';

/* Path the error handler resource files */
$errorResource = 'ErrorHandler/Resources/';

define("CORE_PATH", ROOT_PATH . $vendorPath);
const SYSTEM_PATH = CORE_PATH . 'System/';

const PUBLIC_PATH = ROOT_PATH . '/public';

if (!file_exists(ROOT_PATH . '/storage')) {
    mkdir(ROOT_PATH . '/storage', 0777, true);
}

if (!file_exists(ROOT_PATH . '/storage/logs')) {
    mkdir(ROOT_PATH . '/storage/logs', 0777, true);
}

const STORAGE_PATH = ROOT_PATH . DS . 'storage';
const LOG_PATH = STORAGE_PATH . DS . 'logs';
const CACHE_PATH = STORAGE_PATH . DS;

const APP_ROOT = ROOT_PATH;
const ASSET_PATH = DS . PUBLIC_PATH . DS . 'assets';
const CSS_PATH = PUBLIC_PATH . DS . 'css' . DS;
const JS_PATH = PUBLIC_PATH . DS . 'js' . DS;
const IMAGE_PATH = PUBLIC_PATH . DS . 'images' . DS;


// messages
const MSG_403 = "You are not authorized to access this page.";

// log types
const CRITICAL_LOG = 'critical';
const WARNING_LOG = 'warning';
const EXCEPTION_LOG = 'exception';
const ERROR_LOG = 'error';
const NOTICE_LOG = 'notice';
const INFO_LOG = 'info';
const DEBUG_LOG = 'debug';