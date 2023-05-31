<?php

namespace monolitum\database;

use monolitum\entity\Model;

class Query_Entities extends Query
{

    /*
     * @var string[]
     */
    private $selectAttrs;

    /**
     * @param Manager_DB $dbManager
     * @param Model $model
     */
    public function __construct($dbManager, $model)
    {
        parent::__construct($dbManager, $model);
    }

    /**
     * @param string|array<string>|null $attrs
     * @return $this
     */
    public function select($attrs)
    {
        if(is_string($attrs))
            $this->selectAttrs = [$attrs];
        else if(is_array($attrs))
            $this->selectAttrs = $attrs;
        else
            $this->selectAttrs = null;
        return $this;
    }

    /**
     * Store entities in the manager to be referenced later
     * @return $this
     */
    public function store()
    {
        return $this;
    }

    /**
     * @return string[]
     */
    public function getSelectAttrs()
    {
        return $this->selectAttrs;
    }

}