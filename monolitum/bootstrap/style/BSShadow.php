<?php

namespace monolitum\bootstrap\style;

use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class BSShadow extends ElementComponent_Ext
{

    /**
     * @param string|null $value
     */
    public function __construct($none, $value)
    {
        parent::__construct(function (BSShadow $it) use ($none, $value) {
            if($none) {
                $it->getElementComponent()->setClass("bs_shadow", "shadow-none");
            }else{
                if($value !== null)
                    $it->getElementComponent()->setClass("bs_shadow", "shadow-" . $value);
                else
                    $it->getElementComponent()->setClass("bs_shadow", "shadow");
            }
        });
    }

    /**
     * @return BSShadow
     */
    public static function addSmall(){
        /** @var BSShadow $active */
        $active = GlobalContext::add(BSShadow::small());
        return $active;
    }

    /**
     * @return BSShadow
     */
    public static function small(){
        return new BSShadow(false, "sm");
    }

    /**
     * @return BSShadow
     */
    public static function addRegular(){
        /** @var BSShadow $active */
        $active = GlobalContext::add(BSShadow::regular());
        return $active;
    }

    /**
     * @return BSShadow
     */
    public static function regular(){
        return new BSShadow(false, null);
    }

    /**
     * @return BSShadow
     */
    public static function addLarge(){
        /** @var BSShadow $active */
        $active = GlobalContext::add(BSShadow::large());
        return $active;
    }

    /**
     * @return BSShadow
     */
    public static function large(){
        return new BSShadow(false, "lg");
    }

    /**
     * @return BSShadow
     */
    public static function addNone(){
        /** @var BSShadow $active */
        $active = GlobalContext::add(BSShadow::none());
        return $active;
    }

    /**
     * @return BSShadow
     */
    public static function none(){
        return new BSShadow(true, null);
    }

}