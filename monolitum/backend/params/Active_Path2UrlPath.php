<?php

namespace monolitum\backend\params;

use monolitum\core\Active;
use monolitum\core\panic\DevPanic;

class Active_Path2UrlPath implements Active
{

    /**
     * @var Path
     */
    private $path;

    /**
     * @var string
     */
    private $url;

    /**
     * @var bool
     */
    private $encodeUrl;

    /**
     * TODO future
     * @var bool
     *-/
    private $encrypted;
*/

    public function __construct($path, $encodeUrl)
    {
        $this->path = $path;
        $this->encodeUrl = $encodeUrl;
    }

    /**
     * @return Path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return bool
     */
    public function isEncodeUrl()
    {
        return $this->encodeUrl;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
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