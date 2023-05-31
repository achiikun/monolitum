<?php

namespace monolitum\bootstrap\values;

class BSColor
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

    public static function primary(){
        return new BSColor("primary");
    }

    public static function secondary(){
        return new BSColor("secondary");
    }

    public static function success(){
        return new BSColor("success");
    }

    public static function danger(){
        return new BSColor("danger");
    }

    public static function warning(){
        return new BSColor("warning");
    }

    public static function info(){
        return new BSColor("info");
    }

    public static function light(){
        return new BSColor("light");
    }

    public static function dark(){
        return new BSColor("dark");
    }

    public static function body(){
        return new BSColor("body");
    }

    public static function muted(){
        return new BSColor("muted");
    }

    public static function white(){
        return new BSColor("white");
    }

    public static function transparent(){
        return new BSColor("transparent");
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

}