<?php

namespace monolitum\backend\params;

use monolitum\core\GlobalContext;
use monolitum\entity\ValidatedValue;
use monolitum\backend\Manager;

class Manager_Path extends Manager
{

    /**
     * @var Param
     */
    private $readPathParam;

    /**
     * @var string|false
     */
    private $writeAsParam = false;

    /**
     * @var string[]
     */
    private $path = [];

    /**
     * @var int
     */
    private $nextIdx = 0;

    /**
     * @param Param $pathParam
     * @param callable|null $builder
     */
    public function __construct($pathParam, $builder = null)
    {
        parent::__construct($builder);
        $this->readPathParam = $pathParam;
    }

    /**
     * @param string $paramName
     */
    public function writeAsParam($paramName)
    {
        $this->writeAsParam = $paramName;
    }

    public function getCurrentPathCopy()
    {
        return array_slice($this->path, 0, $this->nextIdx);
    }

    /**
     * @param Active_Get_Path_Top_AndShift $active
     * @return void
     */
    public function shiftElement($active)
    {

        if(count($this->path) > $this->nextIdx){
            $strValue = $this->path[$this->nextIdx];
            $this->nextIdx++;

            $activeType = $active->getType();
            $active->setValidatedValue($this->validatedValueFromStr($activeType, $strValue));

        }else{
            $active->setValidatedValue(new ValidatedValue(false));
        }


    }

    /**
     * @param Active_Get_Path_Top $active
     * @return void
     */
    public function currentElement($active)
    {

        if($this->nextIdx == 0){
            $active->setValidatedValue(new ValidatedValue(false));
        }else if($this->nextIdx > 0 && count($this->path) >= $this->nextIdx){
            $strValue = $this->path[$this->nextIdx-1];

            $activeType = $active->getType();
            $active->setValidatedValue($this->validatedValueFromStr($activeType, $strValue));

        }else{
            $active->setValidatedValue(new ValidatedValue(false));
        }

    }

    protected function buildManager()
    {
        /** @var ValidatedValue $validatedPath */
        $validatedPath = $this->readPathParam->getValidatedValue();
        if($validatedPath->isValid() && !$validatedPath->isNull()){

            /** @var string $path */
            $path = $validatedPath->getValue();

            if(strlen($path) > 0){
                $this->path = explode("/", $path);
            }

        }

        parent::buildManager();
    }

    protected function receiveActive($active)
    {
        if($active instanceof Active_Get_Path_Top_AndShift){
            $active->setManager($this);
            return true;
        }else if($active instanceof Active_Get_Path_Top){
            $active->setManager($this);
            return true;
        }else if($active instanceof Active_Make_Url) {

            /** @var Link|Path $link */
            $link = $active->getLink();

            $isObtainParamsAlone = $active->isObtainParamsAlone();

            $writeAsParam = $active->getWriteAsParam();
            if($writeAsParam === null)
                $writeAsParam = $this->writeAsParam;

            $isAppendUrlPrefix = $active->isAppendUrlPrefix();
            if($isAppendUrlPrefix === null)
                $isAppendUrlPrefix = false;

            $paramsAlone = [];

            if($link instanceof Path){
                $path = $link;
            }else{
                /** @var Path $url */
                $path = $link->getPath();
            }

            $url = "";
            $querySign = false;

            if($isAppendUrlPrefix)
                $url .= GlobalContext::getLocalAddress();

            $stringPath = $path !== null ? $path->writePath() : null;
            if($stringPath != null){
                if($writeAsParam){
                    if($isObtainParamsAlone){
                        $paramsAlone[$writeAsParam] = $stringPath;
                    }else{
                        $url .= '/?' . $writeAsParam . "=" . urlencode($stringPath);
                        $querySign = true;
                    }
                }else{
                    $url .= '/' . $stringPath;
                }
            }else{
                $url .= '/';
            }

            if($link instanceof Link){

                $copy = $link->isCopyParams();

                if($copy !== false){

                    $activeGetParams = new Active_Get_Params(Active_Get_Params::GET, $copy, $link->getRemoveParams());
                    GlobalContext::add($activeGetParams);

                    $currentParams = $activeGetParams->getCurrentParams();

                }else{
                    $currentParams = [];
                }

                foreach($link->getRemoveParams() as $key => $value){
                    unset($currentParams[$key]);
                }

                foreach($link->getAddParams() as $key => $value){
                    $currentParams[$key] = $value;
                }

                if($isObtainParamsAlone){
                    foreach ($currentParams as $key => $value) {
                        $paramsAlone[$key] = $value;
                    }
                }else{

                    foreach ($currentParams as $key => $value){
                        if($key === $writeAsParam || $value === null)
                            continue;

                        if(!$querySign){
                            $url .= '/?';
                            $querySign = true;
                        }else{
                            $url .= '&';
                        }
                        $url .= urlencode($key);
                        $url .= "=";
                        $url .= urlencode($value);
                    }

                }

            }

            // TODO unique changing key?

            $active->setUrl($url);
            if($isObtainParamsAlone)
                $active->setAloneParamValues($paramsAlone);

            return true;
        }else if($active instanceof Active_Get_CurrentPath) {

            $currentLength = count($this->path) - $active->getParents();

            if($currentLength > 0){
                $active->setPath(Path::from(...array_slice($this->path, 0, $currentLength)));
            }

            return true;
        }

        return parent::receiveActive($active);
    }

    public static function encodeParams($params)
    {

        $first = true;
        $url = "";

        foreach ($params as $key => $value){

            if($first)
                $first = false;
            else
                $url .= '&';
            $url .= urlencode($key);
            $url .= "=";
            $url .= urlencode($value);
        }

        return $url;

    }

    /**
     * @param Param $param
     * @param callable|null $builder
     */
    public static function add($param, $builder)
    {
        GlobalContext::add(new Manager_Path($param, $builder));
    }

    /**
     * @return Active_Get_Path_Top_AndShift
     */
    public static function go_Param_Path_ShiftElement(){
        $a = new Active_Get_Path_Top_AndShift(Active_Abstract_ValidatedValue::TYPE_STRING);
        GlobalContext::add($a);
        return $a;
    }

    /**
     * @return Active_Get_Path_Top
     */
    public static function go_Param_Path_CurrentElement(){
        $a = new Active_Get_Path_Top(Active_Abstract_ValidatedValue::TYPE_STRING);
        GlobalContext::add($a);
        return $a;
    }

    /**
     * @return mixed|null
     */
    public static function go_getCurrentElement()
    {
        $a = new Active_Get_Path_Top(Active_Abstract_ValidatedValue::TYPE_STRING);
        GlobalContext::add($a);
        return $a->getValidatedValue()->getValue();
    }

    /**
     * @return Path
     */
    public static function go_buildParent($parents = 0)
    {
        $a = new Active_Get_CurrentPath($parents);
        GlobalContext::add($a);
        return $a->getPath();
    }

    /**
     * @param string $activeType
     * @param string $strValue
     * @return ValidatedValue
     */
    private function validatedValueFromStr($activeType,  $strValue)
    {

        switch ($activeType){
            case Active_Abstract_ValidatedValue::TYPE_STRING:
                return new ValidatedValue(true, true, $strValue, null, $strValue);
            case Active_Abstract_ValidatedValue::TYPE_INT:
                // Dangerous code, it will parse anything. If it fails, a 0 is returned.
                // Better use https://hashids.org/php/ instead of ids
                $intValue = intval($strValue);
                return new ValidatedValue(true, true, $intValue, null, $strValue);
            default:
                return new ValidatedValue(false);
        }
    }

}
