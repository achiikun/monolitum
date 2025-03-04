<?php
namespace monolitum\entity\attr;

use monolitum\entity\ValidatedValue;
use monolitum\entity\values\Color;

class Attr_Color extends Attr
{

    /**
     * @param mixed $value
     * @return ValidatedValue
     */
    public function validate($value)
    {
        if(is_string($value)){
            $parsed = Color::fromHex($value);
            if($parsed !== null){
                return new ValidatedValue(true, true, strlen($value) == 0 ? null : Color::fromHex($value), null, $value);
            }else if($value === "true" || $value === "false"){
                return new ValidatedValue(true, true, $value === "true" ? Color::white() : Color::black(), null, $value);
            }else if(is_numeric($value)){
                $hex = str_pad(dechex(intval($value)), 8, '0', STR_PAD_LEFT);
                return new ValidatedValue(true, true, Color::fromHex($hex), null, $value);
            }
        }else if(is_null($value)){
            return new ValidatedValue(true, true, null, null, "null");
        }
        return new ValidatedValue(false);
    }

    /**
     * @return Attr_Color
     */
    public static function from(){
        return new Attr_Color();
    }

}

