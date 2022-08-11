<?php

namespace Abc\Utility;

class Log
{
    public static $request_number;

    function __construct()
    {

    }

    static function emailErr($msg)
    {
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $file = STORAGE_PATH . '/logs/email_error.txt';
        return self::saveLog($file, $msg);
    }

    private static function saveLog($file, $msg) {
        date_default_timezone_set('Africa/Dar_es_Salaam');

        if (!file_exists(LOG_PATH . '/request_trails/')) {
            mkdir(LOG_PATH . '/request_trails/', 0777, true);
        }

        $log = '[' . date('Y-m-d H:i:s') . '] | ' . self::$request_number . ' | ' . $msg . "\n";
        $syslog_file = STORAGE_PATH . '/logs/request_trails/' . date('Y-m-d') . '_log.txt';
        if ($file == 'default') {
            file_put_contents($syslog_file, $log, FILE_APPEND | LOCK_EX);
            return 1;
        }
        file_put_contents($file, $log, FILE_APPEND | LOCK_EX);
        file_put_contents($syslog_file, $log, FILE_APPEND | LOCK_EX);

        return 1;
    }

    public static function savePlainLog($msg) {
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $log =  $msg . "\n";
        $syslog_file = STORAGE_PATH . '/logs/' . date('Y-m-d') . '_log.txt';
        file_put_contents($syslog_file, $log, FILE_APPEND | LOCK_EX);

        return 1;
    }

    static function sysLog($msg): int
    {
        return self::saveLog('default', $msg);
    }

    static function evo_log($data, $log_type = NOTICE_LOG, $extra_info = null) {
        $log_type_uc = strtoupper($log_type);
        $system_audits_directory = 'system';

        date_default_timezone_set('Africa/Dar_es_Salaam');

        if (!file_exists(LOG_PATH . '/' . $system_audits_directory)) {
            mkdir(LOG_PATH . '/' . $system_audits_directory, 0777, true);
        }

        if ($log_type == 'plain') {
            $log = $data;
        } else {
            $log = '[' . date('Y-m-d H:i:s') . '] | ' . self::$request_number . ' - ' . '[' . $log_type_uc . ']' . ': ' . $data . "\n";
        }
        if ($extra_info !== null && $extra_info !== '') {
            if (!file_exists(LOG_PATH . '/' . $log_type)) {
                mkdir(LOG_PATH . '/' . $log_type, 0777, true);
            }

            $log_trace_file = LOG_PATH . '/' . $log_type . '/' . $log_type . '_trace_' . date('Y-m-d') . '.txt';
            $log_trace = '[' . date('Y-m-d H:i:s') . '] | ' . self::$request_number . ' - ' . '[' . $log_type_uc . ']' . ': ' . "\n" . $extra_info;
            $log_trace .= "\n\n**************************************************************************************************\n\n";
            file_put_contents($log_trace_file, $log_trace, FILE_APPEND | LOCK_EX);
        }

        $system_audits_file = LOG_PATH . '/' . $system_audits_directory . '/' . $system_audits_directory . '_log_' . date('Y-m-d') . '.txt';
        file_put_contents($system_audits_file, $log, FILE_APPEND | LOCK_EX);
    }
}