<?php

namespace monolitum\bootstrap\style;

use monolitum\bootstrap\values\ResponsiveProperty;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class BSColSpan implements ResponsiveProperty
{

    /**
     * @var int
     */
    private $value;

    /**
     * @param int $value
     */
    private function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param int $value
     * @return BSColSpan
     */
    public static function c($value)
    {
        return new BSColSpan($value);
    }

    public static function c12(){
        return new BSColSpan(12);
    }

    public static function c11(){
        return new BSColSpan(11);
    }

    public static function c10(){
        return new BSColSpan(10);
    }

    public static function c9(){
        return new BSColSpan(9);
    }

    public static function c8(){
        return new BSColSpan(8);
    }

    public static function c7(){
        return new BSColSpan(7);
    }

    public static function c6(){
        return new BSColSpan(6);
    }

    public static function c5(){
        return new BSColSpan(5);
    }

    public static function c4(){
        return new BSColSpan(4);
    }

    public static function c3(){
        return new BSColSpan(3);
    }

    public static function c2(){
        return new BSColSpan(2);
    }

    public static function c1(){
        return new BSColSpan(1);
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
     * @return int
     */
    public function getValue($inverted = false)
    {
        return $inverted ? 12 - $this->value : $this->value;
    }

    public function buildInto($component, $inverted = false)
    {
        $component->addClass("col-" . $this->getValue($inverted));
    }

    public function buildIntoResponsive($component, $responsiveValue, $inverted = false)
    {
        $component->addClass("col-" . $responsiveValue . "-" . $this->getValue($inverted));
    }
}