<?php

namespace monolitum\bootstrap;

use monolitum\backend\params\Link;
use monolitum\backend\params\Path;
use monolitum\backend\res\HrefResolver;

class Accordion_Item
{

    /**
     * @var string|BSElementComponent
     */
    private $header;

    /**
     * @var string|BSElementComponent
     */
    private $body;

    /**
     * @var bool
     */
    private $collapsed = true;

    /**
     * @param BSElementComponent|string $header
     */
    public function header($header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @param BSElementComponent|string $body
     */
    public function body($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param bool $collapsed
     */
    public function collapsed($collapsed)
    {
        $this->collapsed = $collapsed;
        return $this;
    }

    /**
     * @return BSElementComponent|string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return BSElementComponent|string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return bool
     */
    public function isCollapsed()
    {
        return $this->collapsed;
    }

    /**
     * @param string|BSElementComponent $header
     * @param string|BSElementComponent $body
     * @param bool $collapsed
     * @return Accordion_Item
     */
    public static function of($header, $body, $collapsed = true){
        $item = new Accordion_Item();
        $item->header($header);
        $item->body($body);
        $item->collapsed($collapsed);
        return $item;
    }

}