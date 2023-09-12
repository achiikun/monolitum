<?php

namespace monolitum\bootstrap;

use monolitum\bootstrap\style\BSText;
use monolitum\bootstrap\values\BSColor;
use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\frontend\component\AbstractText;
use monolitum\frontend\html\HtmlElement;

class Badge extends AbstractText
{

    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement("span", null), $builder);
        $this->addClass("badge");
    }

    /**
     * @param string|Renderable_Node $content
     * @param BSColor $color
     * @return Badge
     */
    public static function of($content, $color)
    {
        $fc = new Badge();
        $fc->push($content);
        $fc->push(BSText::textBackgroundColor($color));
        return $fc;
    }

    /**
     * @param callable $builder
     * @return Badge
     */
    public static function build($builder = null)
    {
        return new Badge($builder);
    }

    /**
     * @param callable $builder
     * @return Badge
     */
    public static function add($builder = null)
    {
        $fc = new Badge($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}