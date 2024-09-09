<?php

namespace monolitum\database;

class Query_Aggregation_Executor extends Query_Aggregation
{

    public function __construct($dbManager, $model, $selectAttr, $operation)
    {
        parent::__construct($dbManager, $model, $selectAttr, $operation);
    }

    /**
     * @return mixed
     */
    public function executeAndClose()
    {
        return $this->getManager()->executeQuery($this);
    }

}
