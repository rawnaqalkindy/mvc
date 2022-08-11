<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Orm;

use Abc\Utility\Log;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

trait DataLayerTrait
{
    /**
     * Returns a flatted array from a multidimensional array
     */
    public function flattenArray(array $array = null): array
    {
        if (is_array($array)) {
            $arraySingle = [];
            foreach ($array as $arr) {
                foreach ($arr as $val) {
                    $arraySingle[] = $val;
                }
            }
            return $arraySingle;
        }
        Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
        exit;
    }

    /**
     * Returns a flatted array from a multidimensional array recursively
     */
    public function flattenArrayRecursive(array $array = null): array
    {
        $flatArray = [];
        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $value) {
            $flatArray[] = $value;
        }
        return $flatArray;
    }

}
