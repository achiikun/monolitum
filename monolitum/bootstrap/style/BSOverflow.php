<?php

namespace monolitum\bootstrap\style;

use monolitum\bootstrap\values\BSBuiltIntoInterface;
use monolitum\bootstrap\values\ResponsiveProperty;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class BSOverflow extends ElementComponent_Ext implements BSBuiltIntoInterface
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
        parent::__construct(null, function (BSOverflow $it){
            $it->buildInto($it->getElementComponent());
        });
        $this->value = $value;
    }

    /**
     * @return BSOverflow
     */
    public static function auto(){
        return new BSOverflow("top");
    }

    /**
     * @return BSOverflow
     */
    public static function hidden(){
        return new BSOverflow("middle");
    }

    /**
     * @return BSOverflow
     */
    public static function visible(){
        return new BSOverflow("bottom");
    }

    /**
     * @return BSOverflow
     */
    public static function scroll(){
        return new BSOverflow("bottom");
    }

    public function add(){
        GlobalContext::add($this);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function buildInto($component, $inverted = false)
    {
        $component->addClass("overflow-" . $this->value);
    }

}