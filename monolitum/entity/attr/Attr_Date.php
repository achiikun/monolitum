<?php
namespace monolitum\entity\attr;

use monolitum\entity\ValidatedValue;

class Attr_Date extends Attr
{

    /**
     * @param mixed $value
     * @return ValidatedValue
     */
    public function validate($value)
    {
        if(is_string($value)){
            if(strlen($value) > 0){

                $date = date_create($value);
                if($date === false)
                    return new ValidatedValue(false);

                return new ValidatedValue(true, true, $date);
            }else{
                return new ValidatedValue(true, true, null);
            }
        }else if(is_null($value)){
            return new ValidatedValue(true, true, null);
        }

        return new ValidatedValue(false);
    }

    /**
     * @return Attr_Date
     */
    public static function of(){
        return new Attr_Date();
    }


}

