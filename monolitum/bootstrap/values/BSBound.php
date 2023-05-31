<?php

namespace monolitum\bootstrap\values;

class BSBound
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

    public static function bottom(){
        return new BSBound("b");
    }

    public static function top(){
        return new BSBound("t");
    }

    public static function right(){
        return new BSBound("e");
    }

    public static function left(){
        return new BSBound("s");
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

}