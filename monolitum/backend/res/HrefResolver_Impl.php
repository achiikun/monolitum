<?php

namespace monolitum\backend\res;

use monolitum\backend\params\Link;
use monolitum\backend\params\Path;

class HrefResolver_Impl implements HrefResolver
{

    /**
     * @var Link|Path
     */
    private $link;

    /**
     * @var bool
     */
    private $obtainParamsAlone;

    /**
     * @var Manager_Href_Resolver
     */
    private $manager;

    /**
     * @var array<string, string>
     */
    private $aloneParamValues = null;

    /**
     * @param Manager_Href_Resolver $manager
     * @param Link|Path $link
     * @param $obtainParamsAlone
     */
    public function __construct($manager, $link, $obtainParamsAlone)
    {
        $this->link = $link;
        $this->manager = $manager;
        $this->obtainParamsAlone = $obtainParamsAlone;
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
    function isObtainParamsAlone()
    {
        return $this->obtainParamsAlone;
    }

    /**
     * @param array<string, string> $paramsAlone
     */
    function setAloneParamValues($paramsAlone)
    {
        $this->aloneParamValues = $paramsAlone;
    }

    /**
     * @return array<string, string>
     */
    public function getAloneParamValues()
    {
        return $this->aloneParamValues;
    }

    function resolve()
    {
        return $this->manager->makeHref($this);
    }
}
