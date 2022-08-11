<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Orm\ClientRepository;

use Abc\ErrorHandler\ErrorHandler;
use Abc\Utility\Log;
use Exception;
use Throwable;
use Abc\Utility\Sortable;
use Abc\Utility\Paginator;
use Abc\Orm\EntityManager\EntityManager;
use Abc\Orm\Exception\DataLayerNoValueException;
use Abc\Orm\EntityManager\EntityManagerInterface;

/**
 * Methods
 * 0. getSchemaID()
 * 1. find(int $id)
 * 2. findAll()
 * 3. findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = [])
 * 4. findOneBy(array $conditions)
 * 5. findObjectBy(array $conditions = [], array $selectors = [])
 * 6. findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = [])
 * 7. findByIdAndDelete(array $conditions)
 * 8. findByIdAndUpdate(array $fields = [], int $id)
 * 9. findWithSearchAndPaging(Object $request, array $args = [])
 * 10. findAndReturn(int $id, array $selectors = []);
 * 11. or404()
 */

class ClientRepository implements ClientRepositoryInterface
{

    use ClientRepositoryTrait;
    use ClientRelationshipTrait;

    protected EntityManagerInterface $em;
    private ?object $findAndReturn;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Returns the entityManager object
     */
    public function getEm(): EntityManagerInterface
    {
        return $this->em;
    }
    /**
     * Checks whether the argument is of the array type else throw an exception
     *
     * @param array $conditions
     * @return void
     */
    private function  isArray(array $conditions): void
    {
        if (!is_array($conditions)) {
            ErrorHandler::exceptionHandler(new Exception('The argument supplied is not an array'), CRITICAL_LOG);
            exit;
        }
    }

    /**
     * Checks whether the argument is set else throw an exception
     */
    private function isEmpty(int $id): void
    {
        if (empty($id)) {
            ErrorHandler::exceptionHandler(new Exception(__METHOD__ . ' method $id argument is empty or invalid. Please address this issue in your code which is calling this method.'), CRITICAL_LOG);
            exit;
        }
    }

    /**
     * A quick and easy way of getting the schemaID within our the controller
     * class, the getSchemaID method is part of the crud interface method. We
     * are simple just referencing that method which will give all controller
     * access to the primary key of the relevant database table.
     */
    public function getSchemaID(): string
    {
        return (string)$this->em->getCrud()->getSchemaID();
    }

    public function findWithRelationship(
        array $selectors,
        array $joinSelectors,
        string $joinTo,
        string $joinType,
        array $conditions = [],
        array $parameters = [],
        array $extras = []
    ) {
        return $this->em->getCrud()->join($selectors, $joinSelectors, $joinTo, $joinType, $conditions, $parameters, $extras);
    }

    public function find(int $id): array
    {
        $this->isEmpty($id);
        try {
            return $this->findOneBy(['id' => $id]);
        } catch (Throwable $throwable) {
            Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
            exit;
//            throw $throwable;
        }
    }

    public function findAll(): array
    {
        try {
            return $this->findBy();
        } catch (Throwable $throwable) {
            Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
            exit;
//            throw $throwable;
        }
    }

    public function findBy(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        try {
            return $this->em->getCrud()->read($selectors, $conditions, $parameters, $optional);
        } catch (Throwable $throwable) {
            Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
            exit;
//            throw $throwable;
        }
    }

    public function findOneBy(array $conditions): array
    {
        $this->isArray($conditions);
        try {
            return $this->em->getCrud()->read([], $conditions);
        } catch (Throwable $throwable) {
            Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
            exit;
//            throw $throwable;
        }
    }

    public function findObjectBy(array $conditions = [], array $selectors = []): ?Object
    {
        $this->isArray($conditions);
        try {
            return $this->em->getCrud()->get($selectors, $conditions);
        } catch (Throwable $ex) {
            ErrorHandler::exceptionHandler(new Exception('The method should have returned an object. But instead nothing has come back. Check that your source contains values. ' . $ex->getMessage()), CRITICAL_LOG);
            exit;
//            throw new DataLayerNoValueException();
        }
    }

    public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $optional = []): array
    {
        $this->isArray($conditions);
        try {
            return $this->em->getCrud()->search($selectors, $conditions, $parameters, $optional);
        } catch (Throwable $throwable) {
            Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
            exit;
//            throw $throwable;
        }
    }

    /**
     * Delete bulk item from a database table by simple providing an array of IDs to
     * which you want to delete.
     * @throws Throwable
     */
    public function findAndDelete(array $items = []): bool
    {
        if (is_array($items) && count($items) > 0) {
            foreach ($items as $item) {
                $delete = $this->findByIDAndDelete(['id' => $item]);
                if ($delete) {
                    return true;
                }
            }
        }
        Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
        return false;
    }

    public function findByIdAndDelete(array $conditions): bool
    {
        $this->isArray($conditions);
        try {
            $result = $this->findObjectBy($conditions);
            if ($result) {
                $delete = $this->em->getCrud()->delete($conditions);
                if ($delete) {
                    return $delete;
                }
            }
        } catch (Throwable $throwable) {
            Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
            exit;
//            throw $throwable;
        }
        return false;
    }

    public function findByIdAndUpdate(array $fields, int $id): bool
    {
        $this->isArray($fields);
        try {
            $result = $this->findOneBy([$this->em->getCrud()->getSchemaID() => $id]);
            if ($result != null && count($result) > 0) {
                $params = (!empty($fields)) ? array_merge([$this->em->getCrud()->getSchemaID() => $id], $fields) : $fields;
                $update = $this->em->getCrud()->update($params, $this->em->getCrud()->getSchemaID());
                if ($update) {
                    return $update;
                }
            }
        } catch (Throwable $throwable) {
            Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
            exit;
//            throw $throwable;
        }
        return false;
    }

    public function findWithSearchAndPaging(Object $request, array $args = [], array $relationship = [])
    {
        list($conditions, $totalRecords) = $this->getCurrentQueryStatus($request, $args);

        $sorting = new Sortable($args['sort_columns']);
        $paging = new Paginator($totalRecords, $args['records_per_page'], $request->query->getInt('page', 1));
        $parameters = ['limit' => $args['records_per_page'], 'offset' => $paging->getOffset()];
        $optional = ['orderby' => $sorting->getColumn() . ' ' . $sorting->getDirection()];

        $searchConditions = [];
        if ($request->query->getAlnum($args['filter_alias'])) {
            $searchRequest = $request->query->getAlnum($args['filter_alias']);
            if (is_array($args['filter_by'])) {
                for ($i = 0; $i < count($args['filter_by']); $i++) {
                    $searchConditions = [$args['filter_by'][$i] => $searchRequest];
                }
            }
            $results = $this->findBySearch($args['filter_by'], $searchConditions);
        } else {
            $queryConditions = array_merge($args['additional_conditions'], $conditions);
            $results = $this->findBy($args['selectors'], $queryConditions, $parameters, $optional);
        }
        return [
            $results,
            $paging->getCurrentPage(),
            $paging->getTotalPages(),
            $totalRecords,
            $sorting->sortDirection(),
            $sorting->sortDescAsc(),
            $sorting->getClass(),
            $sorting->getColumn(),
            $sorting->getDirection()
        ];
    }

    /**
     * Query the database and returns the relevant amount of results based on the set query.
     */
    private function getCurrentQueryStatus(Object $request, array $args): array
    {
        $totalRecords = 0;
        $req = $request->query;
        $status = $req->getAlnum($args['query']);
        $searchResults = $req->getAlnum($args['filter_alias']);
        $conditions = [];
        if ($searchResults) {
            for ($i = 0; $i < count($args['filter_by']); $i++) {
                $conditions = [$args['filter_by'][$i] => $searchResults];
                $totalRecords = $this->em->getCrud()->countRecords($conditions, $args['filter_by'][$i]);
            }
        } else if ($status) {
            $conditions = [$args['query'] => $status];
            $totalRecords = $this->em->getCrud()->countRecords($conditions);
        } elseif (count($args['additional_conditions']) > 0) {
            $conditions = $args['additional_conditions'];
            $totalRecords = $this->em->getCrud()->countRecords($conditions);
        } else {
            $conditions = [];
            $totalRecords = $this->em->getCrud()->countRecords($conditions);
        }
        return [
            $conditions,
            $totalRecords
        ];
    }

    public function findAndReturn(int $id, array $selectors = []): self
    {
        // if (empty($id) || $id === 0) {
        //     throw new DataLayerInvalidArgumentException(__METHOD__ . ' method $id argument is empty or invalid. Please address this issue in your code which is calling this method.');
        // }
        $this->isEmpty($id);
        try {
            $this->findAndReturn = $this->findObjectBy(['id' => $id], $selectors);
            return $this;
        } catch (Throwable $throwable) {
            Log::evo_log('An error occurred at ' . __METHOD__, ERROR_LOG);
            exit;
//            throw $throwable;
        }
    }

    public function or404(): ?object
    {
        if ($this->findAndReturn != null) {
            return $this->findAndReturn;
        } else {
            header('HTTP/1.1 404 not found');
            // $template = new \Abc\Base\BaseView();
            // $template->errorTemplateRender('error/404.twig');
            exit;
        }
    }

    /**
     * Return the amount of item from the specified argument conditions
     */
    public function count(array $conditions = [], ?string $field = 'id')
    {
        return $this->em->getCrud()->countRecords($conditions, $field);
    }

    /**
     * Get the last inserted ID
     * @return int
     */
    public function fetchLastID(): int
    {
        return $this->em->getCrud()->lastID();
    }

    /**
     * Undocumented function
     */
    public function findLatestByQuantity(int $quantity = 5, $orderBy = 'id')
    {
        
    }

}
