<?php

namespace monolitum\bootstrap\values;

class BSWeight
{

    /**
     * @var string|null
     */
    private $value;

    /**
     * @param string|null $value
     */
    private function __construct($value)
    {
        $this->value = $value;
    }

    public static function bold(){
        return new BSWeight("bold");
    }

    public static function bolder(){
        return new BSWeight("bolder");
    }

    public static function semibold(){
        return new BSWeight("semibold");
    }

    public static function normal(){
        return new BSWeight("normal");
    }

    public static function light(){
        return new BSWeight("light");
    }

    public static function lighter(){
        return new BSWeight("lighter");
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

}