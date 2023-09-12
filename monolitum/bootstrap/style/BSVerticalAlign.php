<?php

namespace monolitum\bootstrap\style;

use monolitum\bootstrap\values\BSBuiltIntoInterface;
use monolitum\bootstrap\values\ResponsiveProperty;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class BSVerticalAlign extends ElementComponent_Ext implements ResponsiveProperty, BSBuiltIntoInterface
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
        parent::__construct(null, function (BSVerticalAlign $it){
            $it->buildInto($it->getElementComponent());
        });
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

    public function add(){
        GlobalContext::add($this);
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

    public function buildIntoResponsive($component, $responsiveValue, $inverted = false)
    {
        $component->addClass("align-" . $responsiveValue . "-" . $this->value);
    }
}