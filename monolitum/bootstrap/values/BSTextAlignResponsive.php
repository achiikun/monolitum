<?php

namespace monolitum\bootstrap\values;

class BSTextAlignResponsive extends Responsive implements BSBuiltIntoInterface
{

    /**
     * @param BSTextAlign $def
     */
    public function __construct($def)
    {
        parent::__construct($def);
    }

    /**
     * @param BSTextAlign $sm
     * @return BSTextAlignResponsive
     */
    public function sm($sm)
    {
        return parent::sm($sm); // TODO: Change the autogenerated stub
    }

    /**
     * @param BSTextAlign $md
     * @return BSTextAlignResponsive
     */
    public function md($md)
    {
        return parent::md($md); // TODO: Change the autogenerated stub
    }

    /**
     * @param BSTextAlign $lg
     * @return BSTextAlignResponsive
     */
    public function lg($lg)
    {
        return parent::lg($lg); // TODO: Change the autogenerated stub
    }

    /**
     * @param BSTextAlign $xl
     * @return BSTextAlignResponsive
     */
    public function xl($xl)
    {
        return parent::xl($xl); // TODO: Change the autogenerated stub
    }

    public function buildInto($component)
    {
        parent::_buildInto($component, "text");
    }

}