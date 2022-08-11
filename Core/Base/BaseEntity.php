<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only. 
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Base;

use Abc\ErrorHandler\ErrorHandler;
use Abc\Utility\Log;
use Abc\Utility\Sanitizer;
use Abc\Collection\Collection;
use Exception;

class BaseEntity
{
    protected array $cleanData;
    protected array $dirtyData;
    protected ?object $dataSchemaObject;

    /**
     * BaseEntity constructor.
     * Assign the key which is now a property of this object to its array value
     */
    public function __construct()
    {}

    /**
     * Accept raw untreated data and prepare for sanitization
     */
    public function wash(array $dirtyData): self
    {
        if (empty($dirtyData)) {
            ErrorHandler::exceptionHandler(new Exception($dirtyData . 'has return null which means nothing was submitted'), CRITICAL_LOG);
            exit;
        }
        $this->dirtyData = $dirtyData;
        return $this;
    }

    /**
     * Ensure the data is of the correct data type before passing it through
     * the sanitization class
     */
    public function rinse(): self
    {
        if (!is_array($this->dirtyData)) {
            ErrorHandler::exceptionHandler(
                new Exception(getType($this->dirtyData) . ' is an invalid type for this object. Please return an array of submitted data'), CRITICAL_LOG);
            exit;
        }
        $this->cleanData = Sanitizer::clean($this->dirtyData);
        return $this;
    }

    /**
     * Return the clean data as a new collection object. Also allowing
     * accessing the individual submitted data property. By simple
     * calling the $this->(field_name)
     */
    public function dry(): Collection
    {
        foreach ($this->cleanData as $key => $value) {
            $this->$key = $value;
        }
        return new Collection($this->cleanData);
    }
}
