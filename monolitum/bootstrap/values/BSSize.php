<?php

namespace monolitum\bootstrap\values;

class BSSize
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

    public static function s25(){
        return new BSSize("25");
    }

    public static function s50(){
        return new BSSize("50");
    }

    public static function s75(){
        return new BSSize("75");
    }

    public static function s100(){
        return new BSSize("100");
    }

    public static function auto(){
        return new BSSize("auto");
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

}