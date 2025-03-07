<?php

namespace monolitum\backend\params;

use monolitum\core\Find;
use monolitum\core\GlobalContext;

class Link
{

    const HISTORY_BEHAVIOR_PRESERVE = "preserve";
    const HISTORY_BEHAVIOR_PUSH = "push";
    const HISTORY_BEHAVIOR_POP = "pop";

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
     * @var bool
     */
    private $historyBehavior = self::HISTORY_BEHAVIOR_PRESERVE;

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
     * @param ...$specificParams
     * @return $this
     */
    public function addCopyParams(...$specificParams)
    {
        if($this->copyParams === false){
            $this->setCopyParams(...$specificParams);
        }else if(is_array($this->copyParams)){
            $this->copyParams += $specificParams;
        }
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
        foreach($addParams as $param => $value){
            if (($key = array_search($param, $this->removeParams)) !== false) {
                // Remove from remove
                unset($this->removeParams[$key]);
            }

            if (is_array($this->copyParams) && ($key = array_search($param, $this->copyParams)) !== false) {
                // Remove from copy
                unset($this->copyParams[$key]);
            }

            $this->addParams[$param] = $value;
        }
        return $this;
    }

    /**
     * @param array<string> $removeParams
     * @return $this
     */
    public function removeParams(...$removeParams){
        foreach($removeParams as $param){
            if (key_exists($param, $this->addParams)) {
                // Remove from add
                unset($this->addParams[$param]);
            }else if($this->copyParams === true) {
                $this->removeParams += $removeParams;
            }else if(is_array($this->copyParams) && ($key = array_search($param, $this->copyParams)) !== false){
                // Remove from copyParams
                unset($this->copyParams[$key]);
            }
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function pushHistory()
    {
        $this->historyBehavior = self::HISTORY_BEHAVIOR_PUSH;
        return $this;
    }

    /**
     * @return $this
     */
    public function dontPreserveHistory()
    {
        $this->historyBehavior = null;
        return $this;
    }

    /**
     * @return $this
     */
    public function popHistory()
    {
        $this->historyBehavior = self::HISTORY_BEHAVIOR_POP;
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
     * @return string
     */
    public function getHistoryBehavior()
    {
        return $this->historyBehavior;
    }

    /**
     * @param Path $path
     * @return Link
     */
    public static function from($path = null){
        return new Link($path);
    }

    /**
     * @param Link $fallbackPath
     * @return Link
     */
    public static function fromPopHistory($fallbackPath = null){
        /** @var Manager_History $h */
        $h = Find::sync(Manager_History::class);
        return (new Link($h->getTopHistory($fallbackPath)))->popHistory();
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
        $link->historyBehavior = $this->historyBehavior;
        return $link;
    }

}
