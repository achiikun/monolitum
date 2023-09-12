<?php

namespace monolitum\bootstrap\style;

use monolitum\bootstrap\values\BSColor;
use monolitum\bootstrap\values\BSWeight;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class BSText extends ElementComponent_Ext
{

    /**
     * @param int $sizeNum
     * @return BSText
     */
    public static function addSize($sizeNum){
        /** @var BSText $active */
        $active = GlobalContext::add(BSText::size($sizeNum));
        return $active;
    }

    /**
     * @param int $sizeNum
     * @return BSText
     */
    public static function size($sizeNum)
    {
        return new BSText(function (BSText $it) use ($sizeNum) {
            $it->getElementComponent()->setClass("bs_size", "fs-" . $sizeNum);
        });
    }

    /**
     * @param BSWeight $weight
     * @return BSText
     */
    public static function addWeight($weight){
        /** @var BSText $active */
        $active = GlobalContext::add(BSText::weight($weight));
        return $active;
    }

    /**
     * @param BSWeight $weight
     * @return BSText
     */
    public static function weight($weight)
    {
        return new BSText(function (BSText $it) use ($weight) {
            $it->getElementComponent()->setClass("bs_weight", "fw-" . $weight->getValue());
        });
    }

    /**
     * @param bool $italic
     * @return BSText
     */
    public static function addItalic($italic){
        /** @var BSText $active */
        $active = GlobalContext::add(BSText::italic($italic));
        return $active;
    }

    /**
     * @param bool $italic
     * @return BSText
     */
    public static function italic($italic = true)
    {
        return new BSText(function (BSText $it) use ($italic) {
            if($italic !== null) {
                if ($italic) {
                    $it->getElementComponent()->setClass("bs_italic", "fst-italic");
                } else {
                    $it->getElementComponent()->setClass("bs_italic", "fst-normal");
                }
            }else{
                $it->getElementComponent()->setClass("bs_italic");
            }
        });
    }

    /**
     * @param BSColor $color
     * @return BSText
     */
    public static function addColor($color){
        /** @var BSText $active */
        $active = GlobalContext::add(BSText::color($color));
        return $active;
    }

    /**
     * @param BSColor $color
     * @return BSText
     */
    public static function color($color){
        return new BSText(function (BSText $it) use ($color) {
            $it->getElementComponent()->addClass("text-" . $color->getValue());
        });
    }

    /**
     * @param BSColor $color
     * @return BSText
     */
    public static function addTextBackgroundColor($color){
        /** @var BSText $active */
        $active = GlobalContext::add(BSText::textBackgroundColor($color));
        return $active;
    }

    /**
     * @param BSColor $color
     * @return BSText
     */
    public static function textBackgroundColor($color){
        return new BSText(function (BSText $it) use ($color) {
            $it->getElementComponent()->addClass("text-bg-" . $color->getValue());
        });
    }


    public static function textWrap()
    {
        return new BSText(function (BSText $it) {
            $it->getElementComponent()->addClass("text-wrap");
        });
    }

    public static function textBreak()
    {
        return new BSText(function (BSText $it) {
            $it->getElementComponent()->addClass("text-break");
        });
    }

    public static function textNoWrap()
    {
        return new BSText(function (BSText $it) {
            $it->getElementComponent()->addClass("text-nowrap");
        });
    }

    public static function textTruncate()
    {
        return new BSText(function (BSText $it) {
            $it->getElementComponent()->addClass("text-truncate");
        });
    }

}