<?php

namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;
use monolitum\frontend\html\HtmlElement;

class Card extends BSElementComponent
{

    private $breakpoint;

    public function __construct($builder)
    {
        parent::__construct(new HtmlElement("div"), $builder);
    }

    protected function afterBuildNode()
    {
        $this->addClass("card");

        parent::afterBuildNode();
    }

    /**
     * @param callable $builder
     * @return Card
     */
    public static function add($builder)
    {
        $fc = new Card($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}