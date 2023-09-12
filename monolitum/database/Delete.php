<?php

namespace monolitum\database;

use monolitum\entity\Model;

class Delete
{

    /**
     * @var Manager_DB
     */
    private $manager;

    /**
     * @var Model
     */
    private $model;

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
        $this->manager = $dbManager;
        $this->model = $model;
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
     * @return Manager_DB
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return int
     */
    public function execute()
    {
        return $this->getManager()->executeUpdate($this)[0];
    }

    /**
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter;
    }

}