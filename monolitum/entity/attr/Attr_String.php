<?php
namespace monolitum\entity\attr;

use DateTime;
use monolitum\entity\ValidatedValue;

class Attr_String extends Attr
{

    /**
     * @param mixed $value
     * @return ValidatedValue
     */
    public function validate($value)
    {
        if(is_string($value)){
            return new ValidatedValue(true, strlen($value) == 0 ? null : $value);
        }else if(is_bool($value)){
            return new ValidatedValue(true, $value ? "true" : "false");
        }else if(is_numeric($value)){
            return new ValidatedValue(true, strval($value));
        }else if($value instanceof DateTime){
            return new ValidatedValue(true, date_format($value, DateTime::ATOM));
        }else if(is_null($value)){
            return new ValidatedValue(true, null);
        }
        return new ValidatedValue(false);
    }

    /**
     * @return Attr_String
     */
    public static function of(){
        return new Attr_String();
    }

}

