<?php

namespace monolitum\backend\params;

use monolitum\core\Active;
use monolitum\core\panic\DevPanic;

class Active_Make_Url implements Active
{

    /**
     * @var Link|Path
     */
    private $link;

    /**
     * if true, all params are set alone.
     * Alone params help POST forms to add hidden data into it.
     * @var bool
     */
    private $obtainParamsAlone;

    /**
     * @var array<string, string>
     */
    private $aloneParamValues;

    /**
     * @var null|false|string
     */
    private $writeAsParam = null;

    /**
     * @var bool
     */
    private $appendUrlPrefix = true;

    /**
     * @var string
     */
    private $url;

    /**
     * @param Link|Path $link
     * @param bool $obtainParamsAlone
     */
    public function __construct($link, $obtainParamsAlone=false)
    {
        $this->link = $link;
        $this->obtainParamsAlone = $obtainParamsAlone;
    }

    /**
     * @param false|string|null $writeAsParam
     */
    public function setWriteAsParam($writeAsParam)
    {
        $this->writeAsParam = $writeAsParam;
    }

    /**
     * @return false|string|null
     */
    public function getWriteAsParam()
    {
        return $this->writeAsParam;
    }

    /**
     * @param bool $appendUrlPrefix
     */
    public function setAppendUrlPrefix($appendUrlPrefix)
    {
        $this->appendUrlPrefix = $appendUrlPrefix;
    }

    /**
     * @return bool
     */
    public function isAppendUrlPrefix()
    {
        return $this->appendUrlPrefix;
    }

    /**
     * @return Link|Path
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return bool
     */
    public function isObtainParamsAlone()
    {
        return $this->obtainParamsAlone;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param array<string, string> $paramsAlone
     */
    function setAloneParamValues($paramsAlone)
    {
        $this->aloneParamValues = $paramsAlone;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array<string, string>
     */
    public function getAloneParamValues()
    {
        return $this->aloneParamValues;
    }

    function onNotReceived()
    {
        throw new DevPanic("No path manager.");
    }
}
