<?php

namespace monolitum\bootstrap\values;

class DFlexJustify
{

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    private function __construct($value)
    {
        $this->value = $value;
    }

    public static function start(){
        return new DFlexJustify("start");
    }

    public static function end(){
        return new DFlexJustify("end");
    }

    public static function center(){
        return new DFlexJustify("center");
    }

    public static function between(){
        return new DFlexJustify("between");
    }

    public static function around(){
        return new DFlexJustify("around");
    }

    public static function evenly(){
        return new DFlexJustify("evenly");
    }

    public function getValue(){
        return $this->value;
    }

}