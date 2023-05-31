<?php

namespace monolitum\database;

use monolitum\entity\attr\Attr;
use monolitum\entity\Model;

class Query_Aggregation extends Query
{

    const MAX = "max";
    const MIN = "min";
    const SUM = "sum";
    const COUNT = "count";

    /*
     * @var Attr
     */
    private $selectAttr;

    /*
     * @var string
     */
    private $operation;

    /**
     * @param Manager_DB $dbManager
     * @param Model $model
     * @param Attr $selectAttr
     */
    public function __construct($dbManager, $model, $selectAttr, $operation)
    {
        parent::__construct($dbManager, $model);
        $this->selectAttr = $selectAttr;
        $this->operation = $operation;
    }

    /**
     * @return Attr
     */
    public function getSelectAttr()
    {
        return $this->selectAttr;
    }

    /**
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

}