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

//    /**
//     * @var bool
//     */
//    private $isSetParamsAlone;

    /**
     * @var Manager_Href_Resolver
     */
    private $manager;

//    /**
//     * @var array<string, string>
//     */
//    private $paramsAlone = [];

    /**
     * @param Manager_Href_Resolver $manager
     * @param Link|Path $link
     * @param $isSetParamsAlone
     */
    public function __construct($manager, $link)//, $isSetParamsAlone)
    {
        $this->link = $link;
        $this->manager = $manager;
//        $this->isSetParamsAlone = $isSetParamsAlone;
    }

    /**
     * @return Link|Path
     */
    public function getLink()
    {
        return $this->link;
    }

//    /**
//     * @return bool
//     */
//    public function isSetParamsAlone()
//    {
//        return $this->isSetParamsAlone;
//    }

//    /**
//     * @param array<string, string> $paramsAlone
//     */
//    public function setParamsAlone($paramsAlone)
//    {
//        $this->paramsAlone = $paramsAlone;
//    }

//    /**
//     * @return array<string, string>
//     */
//    public function getParamsAlone()
//    {
//        return $this->paramsAlone;
//    }

    function resolve()
    {
        return $this->manager->makeHref($this);
    }
}