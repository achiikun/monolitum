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
     * @var string|bool
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
     * @param bool|string $paramName
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
     * @param Active_Param_Path_PushValue $active
     * @return void
     */
    public function pushValue($active)
    {

        if(count($this->path) > $this->nextIdx){
            $strValue = $this->path[$this->nextIdx];
            $this->nextIdx++;

            $activeType = $active->getType();
            switch ($activeType){
                case Active_Param_Abstract::TYPE_STRING:
                    $active->setValidatedValue(new ValidatedValue(true, $strValue));
                    break;
                case Active_Param_Abstract::TYPE_INT:
                    // Dangerous code, it will parse anything. If it fails, a 0 is returned.
                    // Better use https://hashids.org/php/ instead of ids
                    $intValue = intval($strValue);
                    $active->setValidatedValue(new ValidatedValue(true, $intValue));
                    break;
                default:
                    $active->setValidatedValue(new ValidatedValue(false));
                    break;
            }
        }else{
            $active->setValidatedValue(new ValidatedValue(false));
        }


    }

    /**
     * @param Active_Param_Path_TopValue $active
     * @return void
     */
    public function topValue($active)
    {

        if($this->nextIdx == 0){
            $active->setValidatedValue(new ValidatedValue(false));
        }else if($this->nextIdx > 0 && count($this->path) >= $this->nextIdx){
            $strValue = $this->path[$this->nextIdx-1];

            $activeType = $active->getType();
            switch ($activeType){
                case Active_Param_Abstract::TYPE_STRING:
                    $active->setValidatedValue(new ValidatedValue(true, $strValue));
                    break;
                case Active_Param_Abstract::TYPE_INT:
                    // Dangerous code, it will parse anything. If it fails, a 0 is returned.
                    // Better use https://hashids.org/php/ instead of ids
                    $intValue = intval($strValue);
                    $active->setValidatedValue(new ValidatedValue(true, $intValue));
                    break;
                default:
                    $active->setValidatedValue(new ValidatedValue(false));
                    break;
            }

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
        if($active instanceof Active_Param_Path_PushValue){
            $active->setManager($this);
            return true;
        }else if($active instanceof Active_Param_Path_TopValue){
            $active->setManager($this);
            return true;
        }else if($active instanceof Active_Make_Url) {

            /** @var Link|Path $link */
            $link = $active->getLink();
            $setParamsAlone = $active->isSetParamsAlone();

            $paramsAlone = [];

            if($link instanceof Path){
                $path = $link;
            }else{
                /** @var Path $path */
                $path = $link->getPath();
            }

            $url = "";
            $querySign = false;

            $stringPath = $this->writePath($path);
            if($stringPath != null){
                if($this->writeAsParam){
                    if($setParamsAlone){
                        $url .= GlobalContext::getLocalAddress();
                        $paramsAlone[$this->writeAsParam] = $stringPath;
                    }else{
                        $url .= GlobalContext::getLocalAddress() . "?" . $this->writeAsParam . "=" . urlencode($stringPath);
                        $querySign = true;
                    }
                }else{
                    $url .= GlobalContext::getLocalAddress() . $stringPath;
                }
            }else{
                $url .= GlobalContext::getLocalAddress();
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

                if($setParamsAlone){
                    $paramsAlone = $currentParams;
                }else{

                    foreach ($currentParams as $key => $value){
                        if($key === $this->writeAsParam)
                            continue;

                        if(!$querySign)
                            $url .= "?";
                        else
                            $url .= "&";
                        $url .= urlencode($key);
                        $url .= "=";
                        $url .= urlencode($value);
                    }

                }

            }

            // TODO unique changing key?

            $active->setUrl($url);
            $active->setParamsAlone($paramsAlone);

            return true;
        }else if($active instanceof Active_Path2UrlPath) {

            /** @var Path $path */
            $path = $active->getPath();
            $url = $this->writePath($path, $active->isEncodeUrl());
            $active->setUrl($url);

            return true;
        }else if($active instanceof Active_Url2Path) {

            /** @var string $path */
            $path = $active->getUrl();

            if(strlen($path) > 0){
                $active->setPath(Path::of(...explode("/", $path)));
            }else{
                $active->setPath(Path::of());
            }

            return true;
        }else if($active instanceof Active_Path_BuildParent) {

            $currentLength = count($this->path) - $active->getParents();

            if($currentLength > 0){
                $active->setPath(Path::of(...array_slice($this->path, 0, $currentLength)));
            }

            return true;
        }

        return parent::receiveActive($active);
    }

    /**
     * @param Path $path;
     * @return string|null
     */
    private function writePath($path, $encodeUrl=true)
    {
        if($path == null){
            return null;
        }else{
            $strings = $path->getPath();
            if($strings){
                $path = "";
                $first = true;
                foreach ($strings as $string) {
                    if($first){
                        $first = false;
                    }else{
                        $path .= "/";
                    }
                    $path .= $encodeUrl ? urlencode($string) : $string;
                }
                return $path;

            }else{
                return null;
            }
        }
    }

    /**
     * @param Param $param
     * @param callable|null $builder
     */
    public static function add($param, $builder)
    {
        GlobalContext::add(new Manager_Path($param, $builder));
    }

    public static function go_Param_Path_PushValue(){
        $a = new Active_Param_Path_PushValue(Active_Param_Abstract::TYPE_STRING);
        GlobalContext::add($a);
        return $a;
    }

    public static function go_Param_Path_TopValue(){
        $a = new Active_Param_Path_TopValue(Active_Param_Abstract::TYPE_STRING);
        GlobalContext::add($a);
        return $a;
    }

    /**
     * @return mixed|null
     */
    public static function go_getTop()
    {
        $a = new Active_Param_Path_TopValue(Active_Param_Abstract::TYPE_STRING);
        GlobalContext::add($a);
        return $a->getValidatedValue()->getValue();
    }

    /**
     * @return Path
     */
    public static function go_buildParent($parents = 0)
    {
        $a = new Active_Path_BuildParent($parents);
        GlobalContext::add($a);
        return $a->getPath();
    }

}