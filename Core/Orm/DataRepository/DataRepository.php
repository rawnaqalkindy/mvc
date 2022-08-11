<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Orm\DataRepository;

use Abc\Orm\EntityManager\EntityManagerInterface;
use Abc\Utility\Log;
use Throwable;

class DataRepository implements DataRepositoryInterface
{
    protected EntityManagerInterface $em;

    /**
     * Main class constructor which requires the entity manager object. This object
     * is passed within the data repository factory.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getClientCrud(): object
    {
        return $this->em->getCrud();
    }

    public function save(array $fields = [], ?string $primaryKey = null): bool
    {
        try {
            if (is_array($fields) && count($fields) > 0) {
                if ($primaryKey != null && is_string($primaryKey)) {
                    return $this->em->getCrud()->update($fields, $primaryKey);
                } elseif ($primaryKey === null) {
                    return $this->em->getCrud()->create($fields);
                }
            }
        } catch (Throwable $throw) {
            Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
            exit;
//            throw $throw;
        }
    }

    public function drop(array $condition): bool
    {
        try {
            if (is_array($condition) && count($condition) > 0) {
                return $this->em->getCrud()->delete($condition);
            }
        } catch (Throwable $throw) {
            Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
            exit;
//            throw $throw;
        }
    }

    public function get(array $conditions = []): array
    {
        try {
            return $this->em->getCrud()->read([], $conditions);
        } catch (Throwable $throw) {
            Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
            exit;
//            throw $throw;
        }
    }


    public function validate(): void
    {
    }
}
