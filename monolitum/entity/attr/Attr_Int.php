<?php
namespace monolitum\entity\attr;

use monolitum\entity\ValidatedValue;

class Attr_Int extends Attr
{
    /**
     * @return Attr_Int
     */
    public static function of(){
        return new Attr_Int();
    }

    public function validate($value)
    {
        if(is_string($value)){
            if(is_numeric($value)) {
                $intValue = intval($value);
                return new ValidatedValue(true, $intValue);
            } else {
                return new ValidatedValue(false);
            }
        }else if(is_numeric($value)){
            return new ValidatedValue(true, intval($value));
        }
        return new ValidatedValue(false);
    }
}

