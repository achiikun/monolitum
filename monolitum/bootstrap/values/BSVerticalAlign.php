<?php

namespace monolitum\bootstrap\values;

class BSVerticalAlign extends ResponsiveProperty implements BSBuiltIntoInterface
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

    /**
     * @return BSVerticalAlign
     */
    public static function top(){
        return new BSVerticalAlign("top");
    }

    /**
     * @return BSVerticalAlign
     */
    public static function middle(){
        return new BSVerticalAlign("middle");
    }

    /**
     * @return BSVerticalAlign
     */
    public static function bottom(){
        return new BSVerticalAlign("bottom");
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
        $component->addClass("align-" . $this->value);
    }
}