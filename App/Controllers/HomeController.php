<?php

namespace App\Controllers;

use Abc\Base\BaseController;

class HomeController extends BaseController
{
    public function index()
    {
//        echo __METHOD__;
        $this->view('home/index');
    }
}