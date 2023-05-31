<?php

namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;
use monolitum\frontend\html\HtmlElement;

class Hr extends BSElementComponent
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