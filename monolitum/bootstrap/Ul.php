<?php

namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;
use monolitum\frontend\html\HtmlElement;

class Ul extends BSElementComponent
{

    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement("ul"), $builder);
    }

    /**
     * @param callable $builder
     * @return Ul
     */
    public static function build($builder = null)
    {
        return new Ul($builder);
    }

    /**
     * @param callable $builder
     * @return Ul
     */
    public static function add($builder = null)
    {
        $fc = new Ul($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}