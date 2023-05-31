<?php

namespace monolitum\bootstrap;

use monolitum\backend\params\Link;
use monolitum\backend\params\Path;

class Nav_Item
{

    /**
     * @var string
     */
    private $text;

    /**
     * @var Link|Path
     */
    private $link;

    /**
     * @var bool
     */
    private $active = false;

    /**
     * @var bool
     */
    private $disabled = false;

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return $this;
     */
    public function text($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return Link|Path
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param Link|Path $link
     * @return $this;
     */
    public function link($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return $this
     */
    public function active($active = true)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     * @return $this
     */
    public function disabled($disabled = true)
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * @param $text
     * @return Nav_Item
     */
    public static function of($text){
        $item = new Nav_Item();
        $item->text($text);
        return $item;
    }

}