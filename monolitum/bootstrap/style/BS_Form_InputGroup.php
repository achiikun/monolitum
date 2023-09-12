<?php

namespace monolitum\bootstrap\style;

use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class BS_Form_InputGroup extends ElementComponent_Ext
{

    public function apply()
    {
        parent::apply();

        $elementComponent = $this->getElementComponent();
        $elementComponent->addClass("input-group");

    }

    public static function add(){
        $it = new BS_Form_InputGroup();
        GlobalContext::add($it);
        return $it;
    }

}