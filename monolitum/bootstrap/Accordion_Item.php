<?php

namespace monolitum\bootstrap;

use monolitum\frontend\ElementComponent;

class Accordion_Item
{

    /**
     * @var string|ElementComponent
     */
    private $header;

    /**
     * @var string|ElementComponent
     */
    private $body;

    /**
     * @var bool
     */
    private $collapsed = true;

    /**
     * @param ElementComponent|string $header
     */
    public function header($header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @param ElementComponent|string $body
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
     * @return ElementComponent|string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return ElementComponent|string
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
     * @param string|ElementComponent $header
     * @param string|ElementComponent $body
     * @param bool $collapsed
     * @return Accordion_Item
     */
    public static function from($header, $body, $collapsed = true){
        $item = new Accordion_Item();
        $item->header($header);
        $item->body($body);
        $item->collapsed($collapsed);
        return $item;
    }

}
