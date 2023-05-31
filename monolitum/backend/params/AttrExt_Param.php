<?php

namespace monolitum\backend\params;

use monolitum\entity\AttrExt;

class AttrExt_Param extends AttrExt
{

    /** @var string */
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}