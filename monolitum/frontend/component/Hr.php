<?php

namespace monolitum\frontend\component;

use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;

class Hr extends ElementComponent
{

    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement("hr"), $builder);
    }

    /**
     * @param callable $builder
     * @return Hr
     */
    public static function add($builder = null)
    {
        $fc = new Hr($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}