<?php

namespace monolitum\bootstrap\style;

use monolitum\bootstrap\values\ResponsiveProperty;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class BSDisplay extends ElementComponent_Ext implements ResponsiveProperty
{

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    function __construct($value)
    {
        parent::__construct(null, function (BSDisplay $it){
            $it->buildInto($it->getElementComponent());
        });
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
     * @return BSDisplay_Flex
     */
    public static function flex(){
        return new BSDisplay_Flex("flex");
    }

    /**
     * @return BSDisplay_Flex
     */
    public static function inline_flex(){
        return new BSDisplay_Flex("inline-flex");
    }

    public function add(){
        GlobalContext::add($this);
    }

    public function buildInto($component, $inverted = false)
    {
        $component->addClass("d-" . $this->getValue($inverted));
    }

    public function buildIntoResponsive($component, $responsiveValue, $inverted = false)
    {
        $component->addClass("d-" . $responsiveValue . "-" . $this->getValue($inverted));
    }

    /**
     * @return string
     */
    public function getValue($inverted=false)
    {
        return $this->value;
    }

}