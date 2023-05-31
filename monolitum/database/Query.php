<?php

namespace monolitum\database;

use monolitum\entity\Model;

class Query
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
     * @var string[]
     */
    private $selectAttrs;

    private $filter;

    private $limitLow;
    private $limitMany;

    /**
     * @var array<string>
     */
    private $sortedAttrs = [];

    /**
     * @var array<bool>
     */
    private $sortedAttrsAsc = [];

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
     * @param array<string, mixed>|mixed $filter
     * @return $this
     */
    public function filter($filter){
        $this->filter = $filter;
        return $this;
    }

    /**
     * @param int $low
     * @param int $high
     * @return $this
     */
    public function limit($low, $high = null){
        if($high == null){
            $this->limitLow = 0;
            $this->limitMany = $low;
        }else{
            $this->limitLow = $low;
            $this->limitMany = $high;
        }
        return $this;
    }

    public function sort($attr, $asc = true){
        $this->sortedAttrs[] = $attr;
        $this->sortedAttrsAsc[] = $asc;
        return $this;
    }

    /**
     * Performs an inner join in the query.
     *
     * @param string|array<string> $attrs
     * @param Join $join other query model
     */
    public function join($attrs, $join = null){

        return $this;
    }

    /**
     * @return string[]
     */
    public function getSelectAttrs()
    {
        return $this->selectAttrs;
    }

    /**
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return mixed
     */
    public function getLimitLow()
    {
        return $this->limitLow;
    }

    /**
     * @return mixed
     */
    public function getLimitMany()
    {
        return $this->limitMany;
    }

    /**
     * @return string[]
     */
    public function getSortedAttrs()
    {
        return $this->sortedAttrs;
    }

    /**
     * @return bool[]
     */
    public function getSortedAttrsAsc()
    {
        return $this->sortedAttrsAsc;
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

}