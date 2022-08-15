<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only.
 * It is not recommended using it in production as it is.
 */

namespace App\Controllers;

use Abc\Base\BaseController;

class HomeController extends BaseController
{
    public function index()
    {
        // echo 'Yeeeey!';
        echo __METHOD__;
        // $this->view('home/index');
    }
}