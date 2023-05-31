<?php

namespace monolitum\backend\params;

use monolitum\core\Active;
use monolitum\core\panic\DevPanic;

class Active_Url2Path implements Active
{

    /**
     * @var string
     */
    private $url;

    /**
     * @var Path
     */
    private $path;

    public function __construct($url)
    {
        $this->url = $url;
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
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    function onNotReceived()
    {
        throw new DevPanic("No path manager.");
    }
}