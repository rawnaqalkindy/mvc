<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Utility;

class Session
{
    /**
     * Store session data upon successful login
     */
    public static function registerUserSession($userID)
    {
//        //Log::evo_log('Registering user session ID ' . $userID, INFO_LOG);
        $_SESSION['user_id'] = $userID;
        $_SESSION['last_login'] = time();
        $_SESSION['is_login'] = true;
    }
}