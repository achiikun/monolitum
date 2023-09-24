<?php

namespace monolitum\frontend\component;

use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\frontend\html\HtmlElement;

class Li extends AbstractText
{

    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement("li"), $builder);
    }

    /**
     * @param string|Renderable_Node $content
     * @return Li
     */
    public static function of($content)
    {
        $fc = new Li();
        $fc->append($content);
        return $fc;
    }

    /**
     * @param callable $builder
     * @return Li
     */
    public static function build($builder = null)
    {
        $fc = new Li($builder);
        return $fc;
    }

    /**
     * @param callable $builder
     * @return Li
     */
    public static function add($builder = null)
    {
        $fc = new Li($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}