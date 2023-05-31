<?php

namespace monolitum\bootstrap\values;

class BSShadow
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

    public static function small(){
        return new BSShadow("sm");
    }

    public static function regular(){
        return new BSShadow(null);
    }

    public static function large(){
        return new BSShadow("lg");
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

}