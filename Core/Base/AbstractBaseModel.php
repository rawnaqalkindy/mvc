<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare (strict_types = 1);

namespace Abc\Base;

use Abc\Utility\Log;

abstract class AbstractBaseModel extends BaseModel
{
    /**
     * return an array of ID for which the system will prevent from being
     * deleted
     */
    abstract public function guardedID() : array;

    public function cleanData($dirtyData): self
    {
        Log::evo_log('Cleaning data');
        $this->sanitizedData = $this->entity->wash($dirtyData)->rinse()->dry();
        return $this;
    }

    private function getSanitizedData(): array
    {
        $data = [];

//        //Log::evo_log('Looping through the sanitized data');
        foreach($this->sanitizedData as $key => $value)
        {
            $data[$key] = $value;
        }
//        //Log::evo_log('Returning sanitized data');
        return $data;
    }

    public function save()
    {
        Log::evo_log('Getting the sanitized data');
        $data_array = $this->getSanitizedData();
        Log::evo_log('Saving the object');
        return $this->repository->getEm()->getCrud()->create($data_array);
    }

    public function update($id)
    {
//        //Log::evo_log('Getting the sanitized data');
        $data_array = $this->getSanitizedData();
//        //Log::evo_log('Updating the object using its ID');
        return $this->repository->findByIdAndUpdate($data_array, $id);
    }

    public function delete($id)
    {
//        //Log::evo_log('Deleting the object using its ID');
        return $this->repository->getEm()->getCrud()->delete(['id' => $id]);
    }

//    protected function relationships($relationships)
//    {
//        if ($relationships) {
////            //Log::evo_log('Relationships exist');
//            foreach ($relationships as $relationship) {
////                print_r($relationship);
//                $this->{$relationship['table_referred_to']} = [];
////                print_r($this->getRelationships($relationship));
//                $this->{$relationship['table_referred_to']} = $this->getRelationships($relationship);
////                print_r($this->{$relationship['table_referred_to']});
//            }
//        }
//    }

    public function getNameForSelectField($id, $field = 'name')
    {
//        //Log::evo_log('Returning the field: ' . $field);
        return $this->getSelectedNameField($id, $field);
    }

    public function isCore($id): bool
    {
        $isCoreStatus =  $this->repository->findObjectBy(['id' => $id], ['is_core']);

        if ($isCoreStatus->is_core === 1) {
//            //Log::evo_log('The object is a core module');
            return true;
        }
        Log::evo_log('The object is not a core module');
        return false;
    }

    public function getState($id)
    {
//        //Log::evo_log('Getting state');
        return $this->repository->findBy(['state_id'], ['id' => $id], [], []);
    }
}
