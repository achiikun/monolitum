<?php

namespace monolitum\bootstrap\style;

use monolitum\bootstrap\values\BSBuiltIntoInterface;
use monolitum\bootstrap\values\BSColor;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class BSBorder extends ElementComponent_Ext implements BSBuiltIntoInterface
{

    /**
     * @var string
     */
    private $which;

    /**
     * @var bool
     */
    private $substractive;

    /**
     * @var BSColor
     */
    private $color;

    /**
     * @var int
     */
    private $width;

    /**
     * @var string
     */
    private $rounded;

    /**
     * @var int
     */
    private $rounded_size;

    /**
     * @param int $size
     * @return $this
     */
    public function width($size){
        $this->width = $size;
        return $this;
    }

    /**
     * @param BSColor $color
     * @return $this
     */
    public function color($color){
        $this->color = $color;
        return $this;
    }

    /**
     * @param int $size
     * @return $this
     */
    public function rounded($size=1){
        $this->rounded_size = $size;
        return $this;
    }

    /**
     * @return $this
     */
    public function rounded_top(){
        $this->rounded = "top";
        return $this;
    }

    /**
     * @return $this
     */
    public function rounded_bottom(){
        $this->rounded = "bottom";
        return $this;
    }

    /**
     * @return $this
     */
    public function rounded_start(){
        $this->rounded = "start";
        return $this;
    }

    /**
     * @return $this
     */
    public function rounded_end(){
        $this->rounded = "end";
        return $this;
    }

    /**
     * @return $this
     */
    public function rounded_circle(){
        $this->rounded = "circle";
        return $this;
    }

    /**
     * @return $this
     */
    public function rounded_pill(){
        $this->rounded = "end";
        return $this;
    }

    /**
     * @param $size int from 1 to 6
     * @param $substractive boolean
     * @return BSBorder
     */
    public static function addAll($size=1, $substractive=false){
        /** @var BSBorder $border */
        $border = GlobalContext::add(BSBorder::all($size, $substractive));
        return $border;
    }

    public static function all($size=1, $substractive=false){
        $border = new BSBorder();
        $border->width=$size;
        $border->substractive=$substractive;
        return $border;
    }

    /**
     * @param $size int from 1 to 6
     * @param $substractive boolean
     * @return BSBorder
     */
    public static function addTop($size=1, $substractive=false){
        /** @var BSBorder $border */
        $border = GlobalContext::add(BSBorder::top($size, $substractive));
        return $border;
    }

    public static function top($size=1, $substractive=false){
        $border = new BSBorder();
        $border->which="top";
        $border->width=$size;
        $border->substractive=$substractive;
        return $border;
    }

    /**
     * @param $size int from 1 to 6
     * @param $substractive boolean
     * @return BSBorder
     */
    public static function addBottom($size=1, $substractive=false){
        /** @var BSBorder $border */
        $border = GlobalContext::add(BSBorder::bottom($size, $substractive));
        return $border;
    }

    public static function bottom($size=1, $substractive=false){
        $border = new BSBorder();
        $border->which="bottom";
        $border->width=$size;
        $border->substractive=$substractive;
        return $border;
    }

    /**
     * @param $size int from 1 to 6
     * @param $substractive boolean
     * @return BSBorder
     */
    public static function addStart($size=1, $substractive=false){
        /** @var BSBorder $border */
        $border = GlobalContext::add(BSBorder::start($size, $substractive));
        return $border;
    }

    public static function start($size=1, $substractive=false){
        $border = new BSBorder();
        $border->which="start";
        $border->width=$size;
        $border->substractive=$substractive;
        return $border;
    }

    /**
     * @param $size int from 1 to 6
     * @param $substractive boolean
     * @return BSBorder
     */
    public static function addEnd($size=1, $substractive=false){
        /** @var BSBorder $border */
        $border = GlobalContext::add(BSBorder::end($size, $substractive));
        return $border;
    }

    /**
     * @param $size int from 1 to 6
     * @param $substractive boolean
     * @return BSBorder
     */
    public static function end($size=1, $substractive=false){
        $border = new BSBorder();
        $border->which="end";
        $border->width=$size;
        $border->substractive=$substractive;
        return $border;
    }

    public function __construct()
    {
        parent::__construct(function (BSBorder $it){
            $it->buildInto($it->getElementComponent());
        });
    }

    public function buildInto($component)
    {
        if($this->width !== null){
            if($this->width == 0)
                $component->addClass("border-0");
            else{
                $component->addClass("border", "border-" . $this->width);
            }
        }else{
            $component->addClass("border");
        }

        if($this->which !== null){
            $component->addClass("border-" . $this->which);
        }

        if($this->color !== null){
            $component->addClass("border-" . $this->color->getValue());
        }

        if($this->rounded !== null){
            $component->addClass("rounded-" . $this->rounded);
        }else if($this->rounded_size !== null){
            $component->addClass("rounded-" . $this->rounded_size);
        }

    }
}