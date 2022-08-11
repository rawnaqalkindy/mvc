<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

const TEMPLATE_EXTENSION = '.html';
const MODULE_NAMESPACE = '_Modules\\';
const ADMIN_NAMESPACE = 'Admin\\';
const MODEL_NAMESPACE = 'App\Models\\';
const CONTROLLER_NAMESPACE = 'App\Controllers\\';
const ENTITY_NAMESPACE = 'App\Entity\\';

const ROUTES = [
    "" => [
        "controller" => "home",
        "action" => "index"
    ],
    "login" => [
        "controller" => "login",
        "action" => "index",
    ],
    "logout" => [
        "controller" => "login",
        "action" => "destroy",
    ],
    "{controller}/{action}" => [
        "namespace" => ""
    ],
    "{controller}/{id:[\da-f]+}/{action}" => [
        "namespace" => ""
    ],
    "admin/{controller}/{action}" => [
        "namespace" => "Admin",
    ],
    "admin/{controller}/{id:[\da-f]+}/{action}" => [
        "namespace" => "Admin",
    ],
];

const DATABASE = [
    "default_driver" => "mysql",
    "drivers" => [
        "mysql" => [
            "class" => "\\Abc\\Orm\\Drivers\\MysqlDatabaseConnection"
        ],
        "pgsql" => [
            "class" => "\\Abc\\Orm\\Drivers\\PgsqlDatabaseConnection"
        ],
        "sqlite" => [
            "class" => "\\Abc\\Orm\\Drivers\\SqliteDatabaseConnection"
        ]
    ]
];



