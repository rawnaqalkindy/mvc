<?php
/*
 * This file is part of the Abc package.
 *
 * (c) John M. Andrew <simplygenius78@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

use Abc\Utility\Log;

Log::evo_log('Wrong directory: ' . realpath(dirname(__FILE__, 1)) . '. Navigate to /public', 'emergency');

echo '<pre>';
echo '<br />';print_r($_SERVER);
echo '</pre>';
