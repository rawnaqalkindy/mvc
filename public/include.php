<?php
/*
 * This file is part of the evo package.
 *
 * (c) John Andrew <simplygenius78@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


/** Path the vendor src directory */


const DS = '/';

define('ROOT_PATH', realpath(dirname(__FILE__, 2)));

/**
 * Load the composer library
 */
$autoload = ROOT_PATH . '/vendor/autoload.php';
const CONFIG_PATH = ROOT_PATH . DS . "config/";

if (is_file($autoload)) {
    require $autoload;
}

require_once CONFIG_PATH . 'AppConfig.php';

require_once CONFIG_PATH . 'SysConstants.php';

require_once CONFIG_PATH . 'SysCoreConfig.php';
require_once CONFIG_PATH . 'SysConfig.php';
