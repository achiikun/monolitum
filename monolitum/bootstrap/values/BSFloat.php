<?php

namespace monolitum\bootstrap\values;

class BSFloat extends ResponsiveProperty
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
}