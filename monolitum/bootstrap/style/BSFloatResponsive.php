<?php

namespace monolitum\bootstrap\style;

use monolitum\bootstrap\values\BSBuiltIntoInterface;
use monolitum\bootstrap\values\Responsive;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\ElementComponent_Ext;

class BSFloatResponsive extends Responsive implements BSBuiltIntoInterface
{

    /**
     * @param BSFloat $def
     */
    public function __construct($def)
    {
        parent::__construct($def);
    }

    /**
     * @param BSFloat $sm
     * @return BSFloatResponsive
     */
    public function sm($sm)
    {
        return parent::sm($sm); // TODO: Change the autogenerated stub
    }

    /**
     * @param BSFloat $md
     * @return BSFloatResponsive
     */
    public function md($md)
    {
        return parent::md($md); // TODO: Change the autogenerated stub
    }

    /**
     * @param BSFloat $lg
     * @return BSFloatResponsive
     */
    public function lg($lg)
    {
        return parent::lg($lg); // TODO: Change the autogenerated stub
    }

    /**
     * @param BSFloat $xl
     * @return BSFloatResponsive
     */
    public function xl($xl)
    {
        return parent::xl($xl); // TODO: Change the autogenerated stub
    }

    /**
     * @param BSFloat $def
     * @return BSFloatResponsive
     */
    public static function of($def = null)
    {
        return new BSFloatResponsive($def);
    }

    public function add(){
        GlobalContext::add(
            new ElementComponent_Ext(
                function (ElementComponent_Ext $it) {
                    $this->buildInto($it->getElementComponent());
                })
        );
    }

    /**
     * @param ElementComponent $component
     * @param bool $inverted
     * @return void
     */
    public function buildInto($component, $inverted = false)
    {
        parent::_buildInto($component, "float", $inverted);
    }

}