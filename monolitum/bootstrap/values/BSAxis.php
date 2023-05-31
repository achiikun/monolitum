<?php

namespace monolitum\bootstrap\values;

class BSAxis
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

    public static function x(){
        return new BSAxis("x");
    }

    public static function y(){
        return new BSAxis("y");
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

}