<?php

namespace monolitum\database;

use monolitum\entity\attr\Attr;
use monolitum\entity\Model;
use monolitum\core\panic\DevPanic;

class Insert
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
     * @var array<string, mixed>
     */
    private $values = [];

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
     * @return Manager_DB
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param Attr|string $attr
     * @param mixed $value
     * @return $this
     */
    public function addValue($attr, $value){
        if($attr instanceof Attr)
            $this->values[$attr->getId()] = $value;
        else if(is_string($attr))
            $this->values[$attr] = $value;
        else
            throw new DevPanic("Attr type not supported");
        return $this;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return array<string, mixed>
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @return int[]
     */
    public function execute()
    {
        return $this->getManager()->executeUpdate($this);
    }

}