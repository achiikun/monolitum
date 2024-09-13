<?php

namespace monolitum\frontend\component;

use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;

class Ul extends ElementComponent
{

    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement("ul"), $builder);
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

    /**
     * @param callable $builder
     * @return Ul
     */
    public static function of($builder = null)
    {
        $fc = new Ul($builder);
        return $fc;
    }

}
