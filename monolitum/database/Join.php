<?php

namespace monolitum\database;

use monolitum\entity\Model;

class Join extends Query
{

    /**
     * @var string[]
     */
    private $localAttrs;

    /**
     * @param Manager_DB $dbManager
     * @param Model $model
     * @param array<string> $attrs
     */
    public function __construct($dbManager, $model, $attrs)
    {
        parent::__construct($dbManager, $model);
        $this->localAttrs = $attrs;
    }

}