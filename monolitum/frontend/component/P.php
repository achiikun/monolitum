<?php

namespace monolitum\frontend\component;

use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\frontend\html\HtmlElement;

class P extends AbstractText
{

    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement("p"), $builder);
    }

    /**
     * @param string|Renderable_Node $content
     * @return P
     */
    public static function from($content)
    {
        $fc = new P();
        $fc->append($content);
        return $fc;

    }

    /**
     * @param callable $builder
     * @return P
     */
    public static function of($builder = null)
    {
        return new P($builder);
    }

    /**
     * @param string|Renderable_Node|callable $builder
     * @return P
     */
    public static function add($builder = null)
    {
        $p = self::of($builder);
        GlobalContext::add($p);
        return $p;
    }

}