<?php

namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class Ext_Row extends ElementComponent_Ext
{

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    public function apply()
    {
        parent::apply();

        $elementComponent = $this->getElementComponent();
        $elementComponent->addClass("row");

    }

    public static function add($builder = null){
        $it = new Ext_Row($builder);
        GlobalContext::add($it);
        return $it;
    }

}