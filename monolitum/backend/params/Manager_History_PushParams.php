<?php

namespace monolitum\backend\params;

use monolitum\backend\Manager;
use monolitum\core\GlobalContext;
use monolitum\entity\Entities_Manager;
use monolitum\entity\Model;

class Manager_History_PushParams extends Manager
{

    /**
     * @var array<string, Model>
     */
    private $pushParameters;

    /**
     * @param Param $readPathParam
     * @param callable $builder
     */
    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param class-string|Model $class
     */
    public function addPushParameters_everythingFromModel($class)
    {
        $model = Entities_Manager::go_getModel($class);
        foreach ($model->getAttrs() as $attr) {
            $this->pushParameters[$attr->getId()] = $model;
        }
    }

    protected function receiveActive($active)
    {
        if($active instanceof Active_Make_Url_WithPushParameters) {

            $active->addPushedParams($this->pushParameters);

            return parent::receiveActive($active);
        }else if($active instanceof Active_Make_Url) {

            $newActive = new Active_Make_Url_WithPushParameters($active->getLink(), $active->isObtainParamsAlone());
            $newActive->setWriteAsParam($active->getWriteAsParam());
            $newActive->addPushedParams($this->pushParameters);

            GlobalContext::add($newActive, $this->getParent());

            $active->setUrl($newActive->getUrl());
            $active->setAloneParamValues($newActive->getAloneParamValues());

            return true;
        }

        return parent::receiveActive($active);
    }

    /**
     * @param callable|null $builder
     */
    public static function add($builder)
    {
        GlobalContext::add(new Manager_History_PushParams($builder));
    }

}
