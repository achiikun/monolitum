<?php

namespace monolitum\backend\res;

use monolitum\core\Active;
use monolitum\backend\params\Path;
use monolitum\core\panic\DevPanic;

class Active_Resolve_Res implements Active
{

    /**
     * @var Path
     */
    private $path;

    /**
     * @var bool
     */
    private $encodeUrl = true;

    /**
     * @var ResResolver
     */
    private $resResolver;

    /**
     * @param Path $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @param bool $encodeUrl
     */
    public function setEncodeUrl($encodeUrl)
    {
        $this->encodeUrl = $encodeUrl;
    }

    /**
     * @return bool
     */
    public function isEncodeUrl()
    {
        return $this->encodeUrl;
    }

    /**
     * @return Path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param ResResolver $resResolver
     */
    public function setResResolver($resResolver)
    {
        $this->resResolver = $resResolver;
    }

    /**
     * @return ResResolver
     */
    public function getResResolver()
    {
        return $this->resResolver;
    }

    function onNotReceived()
    {
        throw new DevPanic("No Active_Resolve_Res found");
    }
}