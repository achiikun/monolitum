<?php

namespace monolitum\bootstrap;

use monolitum\bootstrap\values\BSColSpanResponsive;
use monolitum\bootstrap\values\DFlexJustify;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class Ext_Form_InputGroup extends ElementComponent_Ext
{

    public function apply()
    {
        parent::apply();

        $elementComponent = $this->getElementComponent();
        $elementComponent->addClass("input-group");

    }

    public static function add(){
        $it = new Ext_Form_InputGroup();
        GlobalContext::add($it);
        return $it;
    }

}