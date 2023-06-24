<?php

namespace monolitum\bootstrap\values;

use monolitum\frontend\ElementComponent;

class BSStyle
{

    /**
     * @var ElementComponent
     */
    private $element;

    public function __construct(ElementComponent $element)
    {
        $this->element = $element;
    }

    /**
     * @param int|string $size
     * @param BSBound $bound
     * @return $this
     */
    public function padding($size, $bound = null) {
        if($bound != null)
            $this->element->addClass("p" . $bound->getValue() . "-" . $size);
        else
            $this->element->addClass("p" . "-" . $size);
        return $this;
    }

    /**
     * @param int|float|string $size
     * @return $this
     */
    public function paddingTop($size) {
        return $this->padding($size, BSBound::top());
    }

    /**
     * @param int|float|string $size
     * @return $this
     */
    public function paddingBottom($size) {
        return $this->padding($size, BSBound::bottom());
    }

    /**
     * @param int|float|string $size
     * @return $this
     */
    public function paddingLeft($size) {
        return $this->padding($size, BSBound::left());
    }

    /**
     * @param int|float|string $size
     * @return $this
     */
    public function paddingRight($size) {
        return $this->padding($size, BSBound::right());
    }

    /**
     * @param int|null $size from 0 to 5
     * @param BSBound|BSAxis $bound
     * @return $this
     */
    public function margin($size, $bound = null, $breakpoint = null) {
        if($breakpoint != null){

            if($bound != null)
                if($size == "auto")
                    $this->element->addClass("m" . $bound->getValue() . "-" . $breakpoint . "-auto");
                else if($size < 0)
                    $this->element->addClass("m" . $bound->getValue() . "-" . $breakpoint ."-n" . (-$size));
                else
                    $this->element->addClass("m" . $bound->getValue() . "-" . $breakpoint . "-" . $size);
            else
                $this->element->addClass("m-" . $breakpoint . "-" . $size);

        }else{
            if($bound != null)
                if($size == "auto")
                    $this->element->addClass("m" . $bound->getValue() . "-auto");
                else if($size < 0)
                    $this->element->addClass("m" . $bound->getValue() . "-n" . (-$size));
                else
                    $this->element->addClass("m" . $bound->getValue() . "-" . $size);
            else
                $this->element->addClass("m" . "-" . $size);
        }
        return $this;
    }

    /**
     * @param int|string $size
     * @param string $breakpoint
     * @return $this
     */
    public function marginTop($size, $breakpoint = null) {
        return $this->margin($size, BSBound::top(), $breakpoint);
    }

    /**
     * @param int|string $size
     * @param string $breakpoint
     * @return $this
     */
    public function marginBottom($size, $breakpoint = null) {
        return $this->margin($size, BSBound::bottom(), $breakpoint);
    }

    /**
     * @param int|string $size
     * @param string $breakpoint
     * @return $this
     */
    public function marginLeft($size, $breakpoint = null) {
        return $this->margin($size, BSBound::left(), $breakpoint);
    }

    /**
     * @param int|string $size
     * @param string $breakpoint
     * @return $this
     */
    public function marginRight($size, $breakpoint = null) {
        return $this->margin($size, BSBound::right(), $breakpoint);
    }

    /**
     * @param BSAxis $axis
     * @return $this
     */
    public function paddingAuto($axis) {
        $this->element->addClass("p" . $axis->getValue() . "-auto");
        return $this;
    }

    /**
     * @param BSAxis $axis
     * @return $this
     */
    public function marginAuto($axis) {
        $this->element->addClass("m" . $axis->getValue() . "-auto");
        return $this;
    }

    /**
     * @param BSColor $background
     * @return $this
     */
    public function background($background){
        $this->element->addClass("bg-" . $background->getValue());
        return $this;
    }

    /**
     * @param BSColor $color
     * @return $this
     */
    public function color($color){
        $this->element->addClass("text-" . $color->getValue());
        return $this;
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
     * @param int $number
     * @return $this
     */
    public function size($number){
        $this->element->addClass("fs-" . $number);
        return $this;
    }

    /**
     * @param BSBuiltIntoInterface $border
     * @return $this
     */
    public function border($border){
        $border->buildInto($this->element);
        return $this;
    }

    /**
     * @param BSShadow $size
     * @return $this
     */
    public function shadow($size) {
        if($size != null){
            $v = $size->getValue();
            if($v != null)
                $this->element->addClass("shadow-" . $v);
            else
                $this->element->addClass("shadow");
        }else{
            $this->element->addClass("shadow-none");
        }
        return $this;
    }

    /**
     * @param BSDisplay|BSDisplayResponsive $display
     * @return $this
     */
    public function display($display)
    {
        $display->buildInto($this->element);
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

    public function textWrap()
    {
        $this->element->addClass("text-wrap");
    }

    public function textBreak()
    {
        $this->element->addClass("text-break");
    }

    public function textNoWrap()
    {
        $this->element->addClass("text-nowrap");
    }

    public function textTruncate()
    {
        $this->element->addClass("text-truncate");
    }

}