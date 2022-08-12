<?php

namespace App\Controllers;

use Abc\Base\BaseController;

class HomeController extends BaseController
{
    public function index()
    {
        $this->view('home/index');
    }
}