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
            return new ValidatedValue(true, true, strlen($value) == 0 ? null : Color::ofHex($value));
        }else if(is_bool($value)){
            return new ValidatedValue(true, true, $value ? Color::white() : Color::black());
        }else if(is_int($value)){
            $hex = str_pad(dechex($value), 8, '0', STR_PAD_LEFT);
            return new ValidatedValue(true, true, Color::ofHex($hex));
        }else if(is_null($value)){
            return new ValidatedValue(true, true, null);
        }
        return new ValidatedValue(false);
    }

    /**
     * @return Attr_Color
     */
    public static function of(){
        return new Attr_Color();
    }

}

