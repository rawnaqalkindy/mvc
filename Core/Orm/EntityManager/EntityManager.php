<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Orm\EntityManager;

class EntityManager implements EntityManagerInterface
{
    protected CrudInterface $crud;

    public function __construct(CrudInterface $crud)
    {
        $this->crud = $crud;
    }

    public function getCrud() : Object
    {
        return $this->crud;
    }

}
