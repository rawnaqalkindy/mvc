<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only.
 * It is not recommended using it in production as it is.
 */

namespace App\Controllers;

use Abc\Base\BaseController;
use App\Models\HomeModel;

class HomeController extends BaseController
{
    public function __construct() {
        parent::__construct('Home');
    }

    public function index()
    {
        // echo '<pre>';
        $users = $this->model->read();
        $number_of_users = $this->model->count();

        // echo 'Number of Users: ' . $number_of_users . '<br>';
        // print_r($users);
        // echo '</pre>';

        $this->view('home/index', [
            'users' => $users
        ]);
    }

    public function add()
    {
        $data = [
            'name' => 'Michael Peter',
            'address' => 'Msikitini',
            'location' => 'Kilimani',
        ];

        if($this->model->create($data)) {
            echo 'User added successfully';
        } else {
            echo 'Operation failed!';
        }
    }
}