<?php

namespace monolitum\backend\params;

use monolitum\backend\Manager;
use monolitum\core\GlobalContext;
use monolitum\entity\Entities_Manager;
use monolitum\entity\Model;
use monolitum\entity\ValidatedValue;

class Manager_History extends Manager
{

    /**
     * @var Param
     */
    private $readPathParam;

    /**
     * @var array<Link>
     */
    private $linkStack = array();

    /**
     * @var bool|string
     */
    private $writeAsParam;

    /**
     * @var array<string, Model>
     */
    private $pushParameters = [];

    /**
     * @param Param $readPathParam
     * @param callable $builder
     */
    public function __construct($readPathParam, $builder = null)
    {
        parent::__construct($builder);
        $this->readPathParam = $readPathParam;
    }

    /**
     * @param bool|string $paramName
     */
    public function writeAsParam($paramName)
    {
        $this->writeAsParam = $paramName;
    }

    /**
     * @param Link $fallbackLink
     * @return Link
     */
    public function getTopHistory($fallbackLink)
    {
        if(sizeof($this->linkStack) > 0){
            return $this->linkStack[count($this->linkStack) - 1];
        }else{
            return $fallbackLink;
        }
    }

    /**
     * @param class-string|Model $class
     */
    public function addDefaultPushParameters_everythingFromModel($class)
    {
        $model = Entities_Manager::go_getModel($class);
        foreach ($model->getAttrs() as $attr) {
            $this->pushParameters[$attr->getId()] = $model;
        }
    }

    protected function receiveActive($active)
    {
        if($active instanceof Active_Make_Url) {

            /** @var Link|Path $link */
            $link = $active->getLink();

            if($link instanceof Path){
                return parent::receiveActive($active);
            }

            $copiedLink = null;
            switch($link->getHistoryBehavior()){
                case Link::HISTORY_BEHAVIOR_PRESERVE: {
                    $copiedLink = $link->copy();
                    $copiedLink->removeParams($this->writeAsParam);
                    $hValue = $this->writeHistory($this->linkStack);
                    if(strlen($hValue) > 0){
                        $copiedLink->addParams([
                            $this->writeAsParam => $hValue,
                        ]);
                    }

                } break;
                case Link::HISTORY_BEHAVIOR_PUSH: {
                    $copiedLink = $link->copy();
                    $copiedLink->removeParams($this->writeAsParam);

                    $myPushParams = [];

                    foreach ($this->pushParameters as $paramId => $model){
                        $paramValueActive = new Active_Get_Param_Value(null, $model, $paramId);
                        GlobalContext::add($paramValueActive, $this->getParent());
                        $validatedValue = $paramValueActive->getValidatedValue();
                        if($validatedValue->isValid()){
                            $myPushParams[$paramId] = $validatedValue->getStrValue();
                        }
                    }

                    if($active instanceof Active_Make_Url_WithPushParameters){
                        foreach ($active->getPushedParams() as $paramId => $model){
                            $paramValueActive = new Active_Get_Param_Value(null, $model, $paramId);
                            GlobalContext::add($paramValueActive, $this->getParent());
                            $validatedValue = $paramValueActive->getValidatedValue();
                            if($validatedValue != null && $validatedValue->isValid()){
                                $myPushParams[$paramId] = $validatedValue->getStrValue();
                            }
                        }
                    }

                    $linkStackCopy = $this->linkStack;
                    $linkStackCopy[] = Link::from(Path::ofRelative())->addParams($myPushParams);

                    $hValue = $this->writeHistory($linkStackCopy);
                    if(strlen($hValue) > 0){
                        $copiedLink->addParams([
                            $this->writeAsParam => $hValue,
                        ]);
                    }

                } break;
                case Link::HISTORY_BEHAVIOR_POP: {

                    if(count($this->linkStack) > 0){

                        // TODO check that path is equal to the backup link
                        $linkStackCopy = $this->linkStack;
                        $copiedLink = $linkStackCopy[sizeof($linkStackCopy) - 1]->copy();
                        unset($linkStackCopy[sizeof($linkStackCopy) - 1]);

                        $hValue = $this->writeHistory($linkStackCopy);
                        if(strlen($hValue) > 0){
                            $copiedLink->addParams([
                                $this->writeAsParam => $hValue,
                            ]);
                        }

                    }else{

                        $copiedLink = $link->copy();
                        $copiedLink->removeParams($this->writeAsParam);

                    }

                } break;

            }

            if($copiedLink === null){
                $copiedLink = $link;
            }

            $newActive = new Active_Make_Url($copiedLink, $active->isObtainParamsAlone());
            $newActive->setAloneParamValues($active->getAloneParamValues());
            $newActive->setWriteAsParam($active->getWriteAsParam());

            GlobalContext::add($newActive, $this->getParent());

            $active->setUrl($newActive->getUrl());
            $active->setAloneParamValues($newActive->getAloneParamValues());

            return true;

        }

        return parent::receiveActive($active);
    }

    protected function buildManager()
    {
        /** @var ValidatedValue $validatedPath */
        $validatedPath = $this->readPathParam->getValidatedValue();
        if($validatedPath->isValid() && !$validatedPath->isNull()){
            /** @var string $pathArrayStrStr */
            $pathArrayStrStr = $validatedPath->getValue();
            if(strlen($pathArrayStrStr) > 0){
                $pathArrayStr = explode(" ", $pathArrayStrStr);
                if($pathArrayStr !== false){
                    foreach($pathArrayStr as $pathStr){
                        if(strlen($pathStr) > 0){
                            $decoded = urldecode($pathStr);
                            $this->linkStack[] = Link::fromUrl($decoded);//Active_Transform_Url2Link::from($decoded)->go()->getLink();
                        }
                    }
                }
            }
        }

    }

    /**
     * @param Param $readPathParam
     * @param callable $builder
     */
    public static function add($readPathParam, $builder)
    {
        GlobalContext::add(new Manager_History($readPathParam, $builder));
    }

    private function writeHistory(array $linkStack)
    {
        if(count($linkStack) > 0){
            $string = "";
            $first = true;
            foreach($linkStack as $link){
                if(!$first){
                    $string .= " ";
                }
                $a = new Active_Make_Url($link);
                $a->setWriteAsParam(false);
                $a->setAppendUrlPrefix(false);
                GlobalContext::add($a, $this->getParent());
                $string .= urlencode($a->getUrl());

                $first = false;
            }

            return $string;
        }else{
            return "";
        }
    }

}
