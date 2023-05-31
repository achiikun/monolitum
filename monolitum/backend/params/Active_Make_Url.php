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
     * @var bool
     */
    private $setParamsAlone;

    /**
     * @var string
     */
    private $url;

    /**
     * @var array<string, string>
     */
    private $paramsAlone;

    public function __construct($link, $isSetParamsAlone=false)
    {
        $this->link = $link;
        $this->setParamsAlone = $isSetParamsAlone;
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
    public function isSetParamsAlone()
    {
        return $this->setParamsAlone;
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
    public function setParamsAlone($paramsAlone)
    {
        $this->paramsAlone = $paramsAlone;
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
    public function getParamsAlone()
    {
        return $this->paramsAlone;
    }

    function onNotReceived()
    {
        throw new DevPanic("No path manager.");
    }
}