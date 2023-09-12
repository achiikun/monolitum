<?php

namespace monolitum\bootstrap\style;

use monolitum\bootstrap\values\BSBuiltIntoInterface;
use monolitum\bootstrap\values\ResponsiveProperty;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class BSJustifyContent implements ResponsiveProperty, BSBuiltIntoInterface
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

    public static function start(){
        return new BSJustifyContent("start");
    }

    public static function center(){
        return new BSJustifyContent("center");
    }

    public static function end(){
        return new BSJustifyContent("end");
    }

    public static function between(){
        return new BSJustifyContent("between");
    }

    public static function around(){
        return new BSJustifyContent("around");
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
        return $this->value;
    }

    public function buildInto($component, $inverted = false)
    {
        $component->addClass("justify-content-" . $this->value);
    }

    public function buildIntoResponsive($component, $responsiveValue, $inverted = false)
    {
        $component->addClass("justify-content-" . $responsiveValue . "-" . $this->value);
    }
}