<?php

namespace monolitum\bootstrap\values;

class BSTextAlign extends ResponsiveProperty implements BSBuiltIntoInterface
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
        return new BSTextAlign("start");
    }

    public static function center(){
        return new BSTextAlign("center");
    }

    public static function end(){
        return new BSTextAlign("end");
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
        $component->addClass("text-" . $this->value);
    }
}