<?php

namespace App\Controllers;

use Abc\Base\BaseController;
use App\Base\EmployeeModel;

class EmployeeController extends BaseController{
    public function __construct() {
        parent::__construct('Employee');
    }

    public function index()
    {
        echo '<pre>';
         $employees = $this->model->read();
        $number_of_employees = $this->model->count();

        $data = array(
            'Name' => 'Peter',
            'Address' => 'ComNet',
            'Gender' => 'M',
        );

        $newRecord = $this->model->create($data);
        // exit;
       // $employees = $this->model->read(); 

        echo 'Number of employees: ' . $number_of_employees . '<br>';
        print_r($newRecord);
        print_r($employees);
        echo '</pre>';



        
    }
}