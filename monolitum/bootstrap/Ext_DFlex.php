<?php

namespace monolitum\bootstrap;

use monolitum\bootstrap\values\DFlexJustify;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class Ext_DFlex extends ElementComponent_Ext
{

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param bool $reverse
     * @return $this
     */
    public function row($reverse = false){
        if($reverse)
            $this->getElementComponent()->addClass("flex-row-reverse");
        else
            $this->getElementComponent()->addClass("flex-row");
        return $this;
    }

    /**
     * @param bool $reverse
     * @return $this
     */
    public function col($reverse = false){
        if($reverse)
            $this->getElementComponent()->addClass("flex-column-reverse");
        else
            $this->getElementComponent()->addClass("flex-column");
        return $this;
    }

    /**
     * @param DFlexJustify $justify
     * @return $this
     */
    public function justifyContent($justify){
        $this->getElementComponent()->addClass("justify-content-" . $justify->getValue());
        return $this;
    }

    public function apply()
    {
        parent::apply();

        $elementComponent = $this->getElementComponent();
        $elementComponent->addClass("d-flex");

    }

    public static function add($builder = null){
        $it = new Ext_DFlex($builder);
        GlobalContext::add($it);
        return $it;
    }

}