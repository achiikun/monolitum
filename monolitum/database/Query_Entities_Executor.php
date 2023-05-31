<?php

namespace monolitum\database;

class Query_Entities_Executor extends Query_Entities
{

    /**
     * @var bool
     */
    private $forUpdate;

    public function __construct($dbManager, $entityModel)
    {
        parent::__construct($dbManager, $entityModel);
    }

    /**
     * @return Query_Result
     */
    public function execute()
    {
        return $this->getManager()->executeQuery($this);
    }

    /**
     * @return $this
     */
    public function forUpdate()
    {
        $this->forUpdate = true;
        return $this;
    }

    /**
     * @return bool
     */
    public function isForUpdate()
    {
        return $this->forUpdate;
    }


}