<?php

namespace monolitum\backend\res;

use monolitum\core\Active;
use monolitum\backend\params\Link;
use monolitum\backend\params\Path;
use monolitum\core\panic\DevPanic;

class Active_Create_HrefResolver implements Active
{

    /**
     * @var Link|Path
     */
    private $link;

    /**
     * @var HrefResolver
     */
    private $hrefResolver;
    /**
     * @var bool
     */
    private $setParamsAlone = false;

    /**
     * @param Link|Path $link
     */
    public function __construct($link)
    {
        $this->link = $link;
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
     * @param HrefResolver $hrefResolver
     */
    public function setHrefResolver($hrefResolver)
    {
        $this->hrefResolver = $hrefResolver;
    }

    /**
     * @return HrefResolver
     */
    public function getHrefResolver()
    {
        return $this->hrefResolver;
    }

    function onNotReceived()
    {
        throw new DevPanic("No HrefProvider found");
    }

    public function setParamsAlone($setParamsAlone=true)
    {
        $this->setParamsAlone = $setParamsAlone;
    }
}