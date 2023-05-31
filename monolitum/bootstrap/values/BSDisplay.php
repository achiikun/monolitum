<?php

namespace monolitum\bootstrap\values;

class BSDisplay extends ResponsiveProperty
{

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    protected function __construct($value)
    {
        $this->value = $value;
    }

    public static function none(){
        return new BSDisplay("none");
    }

    public static function inline(){
        return new BSDisplay("inline");
    }

    public static function inline_block(){
        return new BSDisplay("inline-block");
    }

    public static function block(){
        return new BSDisplay("block");
    }

    public static function grid(){
        return new BSDisplay("grid");
    }

    public static function table(){
        return new BSDisplay("table");
    }

    public static function table_cell(){
        return new BSDisplay("table-cell");
    }

    public static function table_row(){
        return new BSDisplay("table-row");
    }

    /**
     * @return BSDisplayFlex
     */
    public static function flex(){
        return new BSDisplayFlex("flex");
    }

    /**
     * @return BSDisplayFlex
     */
    public static function inline_flex(){
        return new BSDisplayFlex("inline-flex");
    }

    public function buildInto($component, $inverted = false)
    {
        parent::_buildInto($component, "d", $inverted);
    }

    /**
     * @return string
     */
    public function getValue($inverted=false)
    {
        return $this->value;
    }

}