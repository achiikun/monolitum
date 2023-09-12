<?php

namespace monolitum\database;

use monolitum\entity\Model;

class Update extends Insert
{
    /**
     * @var <string, mixed>
     */
    private $filter;

    /**
     * @param Manager_DB $dbManager
     * @param Model $model
     */
    public function __construct($dbManager, $model)
    {
        parent::__construct($dbManager, $model);
    }

    /**
     * @param array<string, mixed> $filter
     * @return $this
     */
    public function filter($filter){
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter;
    }

}