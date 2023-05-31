<?php
namespace monolitum\bootstrap;

use monolitum\bootstrap\values\BSStyle;
use monolitum\bootstrap\values\BSBuiltIntoInterface;
use monolitum\bootstrap\values\BSAxis;
use monolitum\bootstrap\values\BSBound;
use monolitum\bootstrap\values\BSColor;
use monolitum\bootstrap\values\BSShadow;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;

class BSElementComponent extends ElementComponent
{
    /**
     * @var BSStyle
     */
    private $bsStyle = null;

    /**
     * @param HtmlElement $element
     * @param callable $builder
     */
    public function __construct($element, $builder = null)
    {
        parent::__construct($element, $builder);
    }

    /**
     * @return BSStyle
     */
    public function bsStyle()
    {
        if($this->bsStyle == null)
            $this->bsStyle = new BSStyle($this);
        return $this->bsStyle;
    }

    /**
     * @param int|string $size
     * @param BSBound $bound
     * @return $this
     */
    public function padding($size, $bound = null) {
        if($bound != null)
            $this->addClass("p" . $bound->getValue() . "-" . $size);
        else
            $this->addClass("p" . "-" . $size);
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
                    $this->addClass("m" . $bound->getValue() . "-" . $breakpoint . "-auto");
                else if($size < 0)
                    $this->addClass("m" . $bound->getValue() . "-" . $breakpoint ."-n" . (-$size));
                else
                    $this->addClass("m" . $bound->getValue() . "-" . $breakpoint . "-" . $size);
            else
                $this->addClass("m-" . $breakpoint . "-" . $size);

        }else{
            if($bound != null)
                if($size == "auto")
                    $this->addClass("m" . $bound->getValue() . "-auto");
                else if($size < 0)
                    $this->addClass("m" . $bound->getValue() . "-n" . (-$size));
                else
                    $this->addClass("m" . $bound->getValue() . "-" . $size);
            else
                $this->addClass("m" . "-" . $size);
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
        $this->addClass("p" . $axis->getValue() . "-auto");
        return $this;
    }

    /**
     * @param BSAxis $axis
     * @return $this
     */
    public function marginAuto($axis) {
        $this->addClass("m" . $axis->getValue() . "-auto");
        return $this;
    }

    /**
     * @param BSColor $background
     * @return $this
     */
    public function background($background){
        $this->addClass("bg-" . $background->getValue());
        return $this;
    }

    /**
     * @param BSColor $color
     * @return $this
     */
    public function color($color){
        $this->addClass("text-" . $color->getValue());
        return $this;
    }

    /**
     * @param BSColor $color
     * @return $this
     */
    public function textBackgrundColor($color){
        $this->addClass("text-bg-" . $color->getValue());
        return $this;
    }

    /**
     * @param BSBuiltIntoInterface $align
     * @return $this
     */
    public function align($align){
        $align->buildInto($this);
        return $this;
    }

    /**
     * @param BSBuiltIntoInterface $border
     * @return $this
     */
    public function border($border){
        $border->buildInto($this);
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
                $this->addClass("shadow-" . $v);
            else
                $this->addClass("shadow");
        }else{
            $this->addClass("shadow-none");
        }
        return $this;
    }

}

