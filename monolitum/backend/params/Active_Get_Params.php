<?php

namespace monolitum\backend\params;

use monolitum\core\Active;
use monolitum\core\panic\DevPanic;

class Active_Get_Params implements Active
{

    const GET = "get";
    const POST = "post";
    //const SESSION = "session";
    //const COOKIE = "cookie";

    /**
     * @var string|string[]
     */
    private $category;

    /**
     * @var bool|string[]
     */
    private $include;

    /**
     * @var string[]
     */
    private $exceptions;

    /**
     * @var array<string, string>
     */
    private $currentParams = [];

    /**
     * @param string|string[] $category
     * @param string[]|null $exceptions
     */
    public function __construct($category, $include, $exceptions)
    {
        $this->category = $category;
        $this->include = $include;
        $this->exceptions = $exceptions;
    }

    /**
     * @return string|string[]
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return bool|string[]
     */
    public function getInclude()
    {
        return $this->include;
    }

    /**
     * @return string[]|null
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }

    /**
     * @param string[] $currentParams
     */
    public function setCurrentParams($currentParams)
    {
        $this->currentParams = $currentParams;
    }

    /**
     * @return string[]
     */
    public function getCurrentParams()
    {
        return $this->currentParams;
    }

    function onNotReceived()
    {
        throw new DevPanic("No Params manager.");
    }
}
