<?php

namespace monolitum\backend\params;

use monolitum\core\Active;
use monolitum\core\panic\DevPanic;

class Active_SetRedirectPath implements Active
{

    /**
     * @var Path|Link
     */
    private $path;

    /**
     * @param Path|Link $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @return Path|Link
     */
    public function getPathOrLink()
    {
        return $this->path;
    }

    function onNotReceived()
    {
        throw new DevPanic("No redirect manager.");
    }
}