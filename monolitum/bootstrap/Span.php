<?php

namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\frontend\html\HtmlElement;

class Span extends AbstractText
{

    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement("span"), $builder);
    }

    /**
     * @param string|Renderable_Node $content
     * @return Span
     */
    public static function of($content)
    {
        $fc = new Span();
        $fc->append($content);
        return $fc;
    }

    /**
     * @param callable $builder
     * @return Span
     */
    public static function build($builder = null)
    {
        $fc = new Span($builder);
        return $fc;
    }

    /**
     * @param callable $builder
     * @return Span
     */
    public static function add($builder = null)
    {
        $fc = new Span($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}