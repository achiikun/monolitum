<?php

namespace monolitum\backend\globals;

use monolitum\backend\Manager;
use monolitum\core\GlobalContext;

class Manager_Globals extends Manager
{

    private $uniqueId = 0;

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    protected function receiveActive($active)
    {

        if($active instanceof Active_NewId){
            $id = $this->uniqueId++;
            $active->setId("uid_" . $id);
            return true;
        }
        return parent::receiveActive($active);
    }

    /**
     * @param callable|null $builder
     */
    public static function add($builder)
    {
        GlobalContext::add(new Manager_Globals($builder));
    }

}