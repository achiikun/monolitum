<?php

namespace monolitum\bootstrap\style;

use monolitum\bootstrap\values\BSBuiltIntoInterface;
use monolitum\bootstrap\values\ResponsiveProperty;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class BSTextAlign extends ElementComponent_Ext implements ResponsiveProperty, BSBuiltIntoInterface
{

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        parent::__construct(null, function (ElementComponent_Ext $it) {
            $this->buildInto($it->getElementComponent());
        });
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
        $component->addClass("text-" . $this->value);
    }

    public function buildIntoResponsive($component, $responsiveValue, $inverted = false)
    {
        $component->addClass("text-" . $responsiveValue . "-" . $this->value);
    }
}