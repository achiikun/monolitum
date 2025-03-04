<?php

namespace monolitum\backend\res;

use monolitum\backend\Manager;
use monolitum\backend\params\Active_Make_Url;
use monolitum\core\GlobalContext;

class Manager_Href_Resolver extends Manager
{

    /**
     * @param callable|null $builder
     */
    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param HrefResolver_Impl $param
     * @return string
     */
    public function makeHref($param)
    {

        $active = new Active_Make_Url($param->getLink(), $param->isObtainParamsAlone());
        GlobalContext::add($active);
        $param->setAloneParamValues($active->getAloneParamValues());
        return $active->getUrl();
    }

    protected function receiveActive($active)
    {
        if($active instanceof Active_Create_HrefResolver){
            $active->setHrefResolver(new HrefResolver_Impl($this, $active->getLink(), $active->isSetParamsAlone()));
            return true;
        }

        return parent::receiveActive($active);
    }

    /**
     * @param callable|null $builder
     */
    public static function add($builder)
    {
        GlobalContext::add(new Manager_Href_Resolver($builder));
    }

}
