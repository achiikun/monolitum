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
            return new ValidatedValue(true, true, strlen($value) == 0 ? null : $value, null, $value);
        }else if(is_bool($value)){
            $value = $value ? "true" : "false";
            return new ValidatedValue(true, true, $value ? "true" : "false", null, $value);
        }else if(is_numeric($value)){
            return new ValidatedValue(true, true, strval($value), null, strval($value));
        }else if($value instanceof DateTime){
            return new ValidatedValue(true, true, date_format($value, DateTime::ATOM), null, $value);
        }else if(is_null($value)){
            return new ValidatedValue(true, true, null, null, "null");
        }
        return new ValidatedValue(false);
    }

    /**
     * @return Attr_String
     */
    public static function from(){
        return new Attr_String();
    }

}

