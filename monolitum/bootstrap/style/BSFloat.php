<?php

namespace monolitum\bootstrap\style;

use monolitum\bootstrap\values\ResponsiveProperty;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class BSFloat implements ResponsiveProperty
{

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    private function __construct($value)
    {
        $this->value = $value;
    }

    public static function right(){
        return new BSFloat("right");
    }

    public static function none(){
        return new BSFloat("none");
    }

    public static function left(){
        return new BSFloat("left");
    }

    public function add(){
        GlobalContext::add(
            new ElementComponent_Ext(
                function (ElementComponent_Ext $it) {
                    $this->buildInto($it->getElementComponent());
                })
        );
    }

    /**
     * @return string
     */
    public function getValue($inverted = false)
    {
        return $inverted ?
            (
                $this->value === "right" ?
                    "left" :
                    ($this->value === "left" ?
                        "right" :
                        $this->value)
            )
            : $this->value;
    }

    public function buildInto($component, $inverted = false)
    {
        $component->addClass("float-" . $this->getValue($inverted));
    }

    public function buildIntoResponsive($component, $responsiveValue, $inverted = false)
    {
        $component->addClass("float-" . $responsiveValue . "-" . $this->getValue($inverted));
    }
}