<?php

namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;
use monolitum\frontend\html\HtmlElement;

class Div extends BSElementComponent
{

    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement("div"), $builder);
    }

    /**
     * @param callable $builder
     * @return Div
     */
    public static function add($builder = null)
    {
        $fc = new Div($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}