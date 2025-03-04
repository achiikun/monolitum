<?php

namespace monolitum\backend\params;

use monolitum\core\Active;
use monolitum\core\GlobalContext;
use monolitum\core\panic\DevPanic;

class Active_Transform_Url2Link implements Active
{

    /**
     * @var string
     */
    private $url;

    /**
     * @var Link
     */
    private $link;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @param string $url
     */
    public static function from($url)
    {
        return new self($url);
    }

    public function go()
    {
        GlobalContext::add($this);
        return $this;
    }

    /**
     * @param Link $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return Link
     */
    public function getLink()
    {
        return $this->link;
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
