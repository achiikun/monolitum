<?php
namespace monolitum\entity\attr;

use monolitum\entity\ValidatedValue;

class Attr_Bool extends Attr
{
    /**
     * @return Attr_Bool
     */
    public static function of() {
        return new Attr_Bool();
    }

    public function validate($value)
    {
        if(is_string($value)){
            if($value == "true"){
                return new ValidatedValue(true, true);
            }else if($value == "false"){
                return new ValidatedValue(true, false);
            }else{
                return new ValidatedValue(true, true);
            }
        }else if(is_numeric($value)){
            if($value == 0) {
                return new ValidatedValue(true, false);
            }else {
                return new ValidatedValue(true, true);
            }
        }else if(is_null($value)){
            return new ValidatedValue(true, false);
        }
        return new ValidatedValue(false);
    }
}

