<?php

namespace monolitum\backend\globals;

use monolitum\backend\Manager;
use monolitum\core\GlobalContext;

class Manager_Globals extends Manager
{

    private $uniqueId = 0;

    private $uniqueIdByContext = [];

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    protected function receiveActive($active)
    {

        if($active instanceof Active_NewId){
            $context = $active->getContextIds();
            if($context === null){

                $id = $this->uniqueId++;
                $active->setId("uid_" . $id);

            }else{

                if(key_exists($context, $this->uniqueIdByContext)){
                    $id = $this->uniqueIdByContext[$context]++;
                }else{
                    $this->uniqueIdByContext[$context] = 1;
                    $id = 0;
                }

                $active->setId("uid_" . $context . "_" . $id);

            }
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