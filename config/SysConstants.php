<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended using it in production as it is.
 */

const CORE_PATH = ROOT_PATH . '/Core/';
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

// messages
const MSG_403 = "You are not authorized to access this page";
const MSG_404 = "PAGE NOT FOUND";
const MSG_500 = "INTERNAL SERVER ERROR";

// titles
const TITLE_403 = "Forbidden";
const TITLE_404 = "Page NOT Found";
const TITLE_500 = "Fatal Error";

// headers
const HEADER_403 = "Unauthorized Access";
const HEADER_404 = "Are you lost?";
const HEADER_500 = "Something is not right...";

// info
const INFO_403 = "You are not allowed to access this content";
const INFO_404 = "We can't seem to find the page you're looking for";
const INFO_500 = "Something went wrong. Contact your Administrator with the Request ID: ";

// log types
const CRITICAL_LOG = 'critical';
const WARNING_LOG = 'warning';
const EXCEPTION_LOG = 'exception';
const ERROR_LOG = 'error';
const NOTICE_LOG = 'notice';
const INFO_LOG = 'info';
const DEBUG_LOG = 'debug';
const DB_LOG = 'database';
const ALERT_LOG = 'alert';
