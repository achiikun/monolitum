<?php

namespace monolitum\bootstrap\style;

use monolitum\bootstrap\values\BSAxis;
use monolitum\bootstrap\values\BSBound;
use monolitum\bootstrap\values\BSColor;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

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
     * @return $this
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
     * @return $this
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
     * @return $this
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
            $this->getElementComponent()->addClass("p" . $axis->getValue() . "-auto");
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
                        $this->getElementComponent()->addClass("m" . $bound->getValue() . "-" . $breakpoint . "-auto");
                    else if ($size < 0)
                        $this->getElementComponent()->addClass("m" . $bound->getValue() . "-" . $breakpoint . "-n" . (-$size));
                    else
                        $this->getElementComponent()->addClass("m" . $bound->getValue() . "-" . $breakpoint . "-" . $size);
                else
                    $this->getElementComponent()->addClass("m-" . $breakpoint . "-" . $size);

            } else {
                if ($bound != null)
                    if ($size == "auto")
                        $this->getElementComponent()->addClass("m" . $bound->getValue() . "-auto");
                    else if ($size < 0)
                        $this->getElementComponent()->addClass("m" . $bound->getValue() . "-n" . (-$size));
                    else
                        $this->getElementComponent()->addClass("m" . $bound->getValue() . "-" . $size);
                else
                    $this->getElementComponent()->addClass("m" . "-" . $size);
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
    public static function marginAuto($axis) {
        return new BSStyle(function (BSStyle $it) use ($axis) {
            $this->getElementComponent()->addClass("m" . $axis->getValue() . "-auto");
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
            $this->getElementComponent()->addClass("bg-" . $background->getValue());
        });
    }

    /**
     * @param BSTextAlign|BSTextAlignResponsive|BSVerticalAlign $align
     * @return $this
     */
    public function align($align){
        $align->buildInto($this->element);
        return $this;
    }

    /**
     * @param BSDisplay|BSDisplayResponsive $display
     * @return $this
     */
    public function display($display)
    {
        $display->buildInto($this->getElementComponent());
        return $this;
    }

    /**
     * @param $float BSFloat|BSFloatResponsive
     * @return $this
     */
    public function float($float)
    {
        $float->buildInto($this->element);
        return $this;
    }

    public function float_start()
    {
        $this->element->addClass("float-start");
        return $this;
    }

    public function float_end()
    {
        $this->element->addClass("float-end");
        return $this;
    }

    public function position_relative()
    {
        $this->element->addClass("position-relative");
        return $this;
    }

    public function position_absolute()
    {
        $this->element->addClass("position-absolute");
        return $this;
    }

    public function top($inside=true)
    {
        if($inside === null){
            $this->element->addClass("top-0", "translate-middle-y");
        }else if($inside)
            $this->element->addClass("top-0");
        else
            $this->element->addClass("bottom-100");
        return $this;
    }

    public function bottom($inside=true)
    {
        if($inside === null){
            $this->element->addClass("top-100", "translate-middle-y");
        }else if($inside)
            $this->element->addClass("bottom-0");
        else
            $this->element->addClass("top-100");
        return $this;
    }

    public function middle($fromTop=null)
    {
        if($fromTop === null){
            $this->element->addClass("top-50", "translate-middle-y");
        }else if($fromTop)
            $this->element->addClass("top-50");
        else
            $this->element->addClass("bottom-50");
        return $this;
    }

    public function start($inside=true)
    {
        if($inside === null){
            $this->element->addClass("start-0", "translate-middle-x");
        }else if($inside)
            $this->element->addClass("start-0");
        else
            $this->element->addClass("end-100");
        return $this;
    }

    public function end($inside=true)
    {
        if($inside === null){
            $this->element->addClass("start-100", "translate-middle-x");
        }else if($inside)
            $this->element->addClass("end-0");
        else
            $this->element->addClass("start-100");
        return $this;
    }

    public function center($fromStart=null)
    {
        if($fromStart === null){
            $this->element->addClass("start-50", "translate-middle-x");
        }else if($fromStart)
            $this->element->addClass("end-50");
        else
            $this->element->addClass("start-50");
        return $this;
    }

}