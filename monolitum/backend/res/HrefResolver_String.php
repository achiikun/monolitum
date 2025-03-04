<?php

namespace monolitum\backend\res;

use monolitum\backend\params\Link;
use monolitum\backend\params\Path;
use monolitum\backend\res\HrefResolver;
use monolitum\backend\res\Manager_Href_Resolver;

class HrefResolver_String implements HrefResolver
{

    /**
     * @var string
     */
    private $string;

    /**
     * @param $string
     */
    public function __construct($string)
    {
        $this->string = $string;
    }

    function resolve()
    {
        return $this->string;
    }

    function getAloneParamValues()
    {
        return null;
    }
}
