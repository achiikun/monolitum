<?php

namespace monolitum\bootstrap\style;

use monolitum\bootstrap\values\BSAxis;
use monolitum\bootstrap\values\BSBound;
use monolitum\bootstrap\values\BSColor;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;
use monolitum\bootstrap\values\BSSize;

class BSStyle extends ElementComponent_Ext
{

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param int|string $size
     * @param BSBound $bound
     * @return void
     */
    public static function addPadding($size, $bound = null){
        GlobalContext::add(BSStyle::padding($size, $bound));
    }

    /**
     * @param BSSize $size
     * @return BSStyle
     */
    public static function width($size)
    {
        return new BSStyle(function (BSStyle $it) use ($size) {
            $it->getElementComponent()->addClass("w" . "-" . $size->getValue());
        });
    }

    /**
     * @param BSSize $size
     * @return BSStyle
     */
    public static function height($size)
    {
        return new BSStyle(function (BSStyle $it) use ($size) {
            $it->getElementComponent()->addClass("h" . "-" . $size->getValue());
        });
    }

    /**
     * @param int|string $size
     * @param BSBound $bound
     * @return BSStyle
     */
    public static function padding($size, $bound = null)
    {
        return new BSStyle(function (BSStyle $it) use ($size, $bound) {
            if($bound != null)
                $it->getElementComponent()->addClass("p" . $bound->getValue() . "-" . $size);
            else
                $it->getElementComponent()->addClass("p" . "-" . $size);
        });
    }

    /**
     * @param int|float|string $size
     * @return void
     */
    public static function addPaddingTop($size) {
        GlobalContext::add(BSStyle::paddingTop($size));
    }

    /**
     * @param int|float|string $size
     * @return BSStyle
     */
    public static function paddingTop($size) {
        return BSStyle::padding($size, BSBound::top());
    }

    /**
     * @param int|float|string $size
     * @return void
     */
    public static function addPaddingBottom($size) {
        GlobalContext::add(BSStyle::paddingBottom($size));
    }

    /**
     * @param int|float|string $size
     * @return BSStyle
     */
    public static function paddingBottom($size) {
        return BSStyle::padding($size, BSBound::bottom());
    }

    /**
     * @param int|float|string $size
     * @return void
     */
    public static function addPaddingLeft($size) {
        GlobalContext::add(BSStyle::paddingLeft($size));
    }

    /**
     * @param int|float|string $size
     * @return BSStyle
     */
    public static function paddingLeft($size) {
        return BSStyle::padding($size, BSBound::left());
    }

    /**
     * @param int|float|string $size
     * @return void
     */
    public static function addPaddingRight($size) {
        GlobalContext::add(BSStyle::paddingRight($size));
    }

    /**
     * @param int|float|string $size
     * @return BSStyle
     */
    public static function paddingRight($size) {
        return BSStyle::padding($size, BSBound::right());
    }

    /**
     * @param BSAxis $axis
     * @return void
     */
    public static function addPaddingAuto($axis) {
        GlobalContext::add(BSStyle::paddingAuto($axis));
    }

    /**
     * @param BSAxis $axis
     * @return BSStyle
     */
    public static function paddingAuto($axis) {
        return new BSStyle(function (BSStyle $it) use ($axis) {
            $it->getElementComponent()->addClass("p" . $axis->getValue() . "-auto");
        });
    }

    /**
     * @param int|null $size from 0 to 5
     * @param BSBound|BSAxis $bound
     * @return void
     */
    public static function addMargin($size, $bound = null, $breakpoint = null) {
        GlobalContext::add(BSStyle::margin($size, $bound, $breakpoint));
    }

    /**
     * @param int|null $size from 0 to 5
     * @param BSBound|BSAxis $bound
     * @return BSStyle
     */
    public static function margin($size, $bound = null, $breakpoint = null) {
        return new BSStyle(function (BSStyle $it) use ($size, $bound, $breakpoint) {
            if ($breakpoint != null) {

                if ($bound != null)
                    if ($size == "auto")
                        $it->getElementComponent()->addClass("m" . $bound->getValue() . "-" . $breakpoint . "-auto");
                    else if ($size < 0)
                        $it->getElementComponent()->addClass("m" . $bound->getValue() . "-" . $breakpoint . "-n" . (-$size));
                    else
                        $it->getElementComponent()->addClass("m" . $bound->getValue() . "-" . $breakpoint . "-" . $size);
                else
                    $it->getElementComponent()->addClass("m-" . $breakpoint . "-" . $size);

            } else {
                if ($bound != null)
                    if ($size == "auto")
                        $it->getElementComponent()->addClass("m" . $bound->getValue() . "-auto");
                    else if ($size < 0)
                        $it->getElementComponent()->addClass("m" . $bound->getValue() . "-n" . (-$size));
                    else
                        $it->getElementComponent()->addClass("m" . $bound->getValue() . "-" . $size);
                else
                    $it->getElementComponent()->addClass("m" . "-" . $size);
            }
        });
    }

    /**
     * @param int|string $size
     * @param string $breakpoint
     * @return BSStyle
     */
    public static function marginTop($size, $breakpoint = null) {
        return BSStyle::margin($size, BSBound::top(), $breakpoint);
    }

    /**
     * @param int|string $size
     * @param string $breakpoint
     * @return BSStyle
     */
    public static function marginBottom($size, $breakpoint = null) {
        return BSStyle::margin($size, BSBound::bottom(), $breakpoint);
    }

    /**
     * @param int|string $size
     * @param string $breakpoint
     * @return BSStyle
     */
    public static function marginLeft($size, $breakpoint = null) {
        return BSStyle::margin($size, BSBound::left(), $breakpoint);
    }

    /**
     * @param int|string $size
     * @param string $breakpoint
     * @return BSStyle
     */
    public static function marginRight($size, $breakpoint = null) {
        return BSStyle::margin($size, BSBound::right(), $breakpoint);
    }

    /**
     * @param BSAxis $axis
     * @return void
     */
    public static function addMarginAuto($axis) {
        GlobalContext::add(BSStyle::marginAuto($axis));
    }

    /**
     * @param BSAxis $axis
     * @return BSStyle
     */
    public static function marginAuto($axis = null) {
        return new BSStyle(function (BSStyle $it) use ($axis) {
            $it->getElementComponent()->addClass("m" . ($axis != null ? $axis->getValue() : "") . "-auto");
        });
    }

    /**
     * @param BSColor $axis
     * @return void
     */
    public static function addBackground($background) {
        GlobalContext::add(BSStyle::background($background));
    }

    /**
     * @param BSColor $background
     * @return BSStyle
     */
    public static function background($background){
        return new BSStyle(function (BSStyle $it) use ($background) {
            $it->getElementComponent()->addClass("bg-" . $background->getValue());
        });
    }

    /**
     * @param BSTextAlign|BSTextAlignResponsive|BSVerticalAlign $align
     * @return BSStyle
     */
    public static function align($align){
        return new BSStyle(function (BSStyle $it) use ($align) {
            $align->buildInto($it->getElementComponent());
        });
    }
//
//    /**
//     * @param BSDisplay|BSDisplayResponsive $display
//     * @return $this
//     */
//    public function display($display)
//    {
//        $display->buildInto($this->getElementComponent());
//        return $this;
//    }
//
    /**
     * @param $float BSFloat|BSFloatResponsive
     * @return $this
     */
    public static function float($float)
    {
        return new BSStyle(function (BSStyle $it) use ($float) {
            $float->buildInto($it->getElementComponent());
        });
    }

    public function float_start()
    {
        $this->getElementComponent()->addClass("float-start");
        return BSStyle::float(BSFloat::start());
    }

    public function float_end()
    {
        $this->getElementComponent()->addClass("float-end");
        return $this;
    }

    public static function position_relative()
    {
        return new BSStyle(function (BSStyle $it) {
            $it->getElementComponent()->addClass("position-relative");
        });
    }

    public static function position_absolute()
    {
        return new BSStyle(function (BSStyle $it) {
            $it->getElementComponent()->addClass("position-absolute");
        });
    }

    public static function top($inside=true)
    {
        return new BSStyle(function (BSStyle $it) use ($inside) {
            if ($inside === null) {
                $it->getElementComponent()->addClass("top-0", "translate-middle-y");
            } else if ($inside)
                $it->getElementComponent()->addClass("top-0");
            else
                $it->getElementComponent()->addClass("bottom-100");
        });
    }

    public static function bottom($inside=true)
    {
        return new BSStyle(function (BSStyle $it) use ($inside) {
        if($inside === null){
            $it->getElementComponent()->addClass("top-100", "translate-middle-y");
        }else if($inside)
            $it->getElementComponent()->addClass("bottom-0");
        else
            $it->getElementComponent()->addClass("top-100");
        });
    }

    public static function middle($fromTop=null)
    {
        return new BSStyle(function (BSStyle $it) use ($fromTop) {
        if($fromTop === null){
            $it->getElementComponent()->addClass("top-50", "translate-middle-y");
        }else if($fromTop)
            $it->getElementComponent()->addClass("top-50");
        else
            $it->getElementComponent()->addClass("bottom-50");
        });
    }

    public static function start($inside=true)
    {
        return new BSStyle(function (BSStyle $it) use ($inside) {
        if($inside === null){
            $it->getElementComponent()->addClass("start-0", "translate-middle-x");
        }else if($inside)
            $it->getElementComponent()->addClass("start-0");
        else
            $it->getElementComponent()->addClass("end-100");
        });
    }

    public static function end($inside=true)
    {
        return new BSStyle(function (BSStyle $it) use ($inside) {
        if($inside === null){
            $it->getElementComponent()->addClass("start-100", "translate-middle-x");
        }else if($inside)
            $it->getElementComponent()->addClass("end-0");
        else
            $it->getElementComponent()->addClass("start-100");
        });
    }

    public static function center($fromStart=null)
    {
        return new BSStyle(function (BSStyle $it) use ($fromStart) {
        if($fromStart === null){
            $it->getElementComponent()->addClass("start-50", "translate-middle-x");
        }else if($fromStart)
            $it->getElementComponent()->addClass("end-50");
        else
            $it->getElementComponent()->addClass("start-50");
        });
    }

}