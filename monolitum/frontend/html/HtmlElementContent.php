<?php
/*
 * Copyright 2008 Sven Sanzenbacher
 *
 * This file is part of the naucon package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace monolitum\frontend\html;

use monolitum\core\Renderable;

/**
 * Html Element Content Class
 *
 * @package    HtmlBuilder
 * @author     Sven Sanzenbacher
 */
class HtmlElementContent implements Renderable
{
    /**
     * @access      protected
     * @var         string                  html element content
     */
    protected $content = null;


    /**
     * @var bool
     */
    protected $raw = false;

    /**
     * Contructor
     *
     * @param       string      $content    html element content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }



    /**
     * @return      string                  html element content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param       string      $content    html element content
     * @return      HtmlElementContent
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRaw()
    {
        return $this->raw;
    }

    /**
     * @param bool $raw
     * @return $this
     */
    public function setRaw($raw=true)
    {
        $this->raw = $raw;
        return $this;
    }

    function renderTo($element)
    {
        if($element instanceof HtmlElement)
            $element->addChildElement($this);
    }
}