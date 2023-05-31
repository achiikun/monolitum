<?php

namespace monolitum\bootstrap\values;

class BSColSpan extends ResponsiveProperty
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
}