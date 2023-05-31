<?php

namespace monolitum\bootstrap\values;

class BSBorder implements BSBuiltIntoInterface
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

    public static function all($size=1, $substractive=false){
        $border = new BSBorder();
        $border->width=$size;
        $border->substractive=$substractive;
        return $border;
    }

    public static function top($size=1, $substractive=false){
        $border = new BSBorder();
        $border->which="top";
        $border->width=$size;
        $border->substractive=$substractive;
        return $border;
    }

    public static function bottom($size=1, $substractive=false){
        $border = new BSBorder();
        $border->which="bottom";
        $border->width=$size;
        $border->substractive=$substractive;
        return $border;
    }

    public static function start($size=1, $substractive=false){
        $border = new BSBorder();
        $border->which="start";
        $border->width=$size;
        $border->substractive=$substractive;
        return $border;
    }

    public static function end($size=1, $substractive=false){
        $border = new BSBorder();
        $border->which="end";
        $border->width=$size;
        $border->substractive=$substractive;
        return $border;
    }

    public function buildInto($component)
    {
        if($this->width !== null){
            if($this->width == 0)
                $component->addClass("border-0");
            else
                $component->addClass("border-" . $this->width);
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