<?php

namespace monolitum\bootstrap\values;

class BSJustifyContent extends ResponsiveProperty implements BSBuiltIntoInterface
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
}