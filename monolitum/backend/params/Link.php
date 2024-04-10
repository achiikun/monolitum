<?php

namespace monolitum\backend\params;

use monolitum\core\GlobalContext;

class Link
{

    /**
     * @var Path
     */
    private $path;

    /**
     * @var bool|string[]
     */
    private $copyParams = false;

    /**
     * @var string[]
     */
    private $removeParams = [];

    /**
     * @var array<string, string>
     */
    private $addParams = [];

    /**
     * @param Path $path
     */
    public function __construct($path = null)
    {
        if($path === null){
            $this->path = Path::ofRelative(0);
        }else{
            $this->path = $path;
        }
    }

    /**
     * @param string[] $specificParams
     * @return $this
     */
    public function setCopyParams(...$specificParams)
    {
        $this->copyParams = !$specificParams ? true : $specificParams;
        return $this;
    }

    /**
     * @param string[] $exceptions
     * @return $this
     */
    public function setCopyParamsExcept(...$exceptions)
    {
        $this->copyParams = true;
        $this->removeParams += $exceptions;
        return $this;
    }

    /**
     * @return $this
     */
    public function setCopyAllParams()
    {
        $this->copyParams = true;
        return $this;
    }

    /**
     * @param array<string, string> $addParams
     * @return $this
     */
    public function addParams($addParams){
        $this->addParams += $addParams;
        return $this;
    }

    /**
     * @param array<string> $removeParams
     * @return $this
     */
    public function removeParams(...$removeParams){
        $this->removeParams += $removeParams;
        return $this;
    }

    /**
     * @param Path $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return Path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return bool|string[]
     */
    public function isCopyParams()
    {
        return $this->copyParams;
    }

    /**
     * @return string[]
     */
    public function getAddParams()
    {
        return $this->addParams;
    }

    /**
     * @return string[]
     */
    public function getRemoveParams()
    {
        return $this->removeParams;
    }

    /**
     * @param Path $path
     * @return Link
     */
    public static function of($path = null){
        return new Link($path);
    }

    public function go_redirect(){
        $active = new Active_SetRedirectPath($this);
        GlobalContext::add($active);
    }

    public function copy()
    {
        $link = new Link($this->path);
        $link->copyParams = $this->copyParams;
        $link->addParams = $this->addParams;
        $link->removeParams = $this->removeParams;
        return $link;
    }

}