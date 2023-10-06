<?php
namespace monolitum\entity;

use http\Params;
use monolitum\core\panic\DevPanic;
use monolitum\frontend\form\AttrExt_Form_String;

class AttrExt_Validate_Int extends AttrExt_Validate
{

    private $min = null;
    private $max = null;

    /**
     * @param int $int
     * @return $this
     */
    public function min($int)
    {
        $this->min = $int;
        return $this;
    }

    /**
     * @param int $int
     * @return $this
     */
    public function max($int)
    {
        $this->max = $int;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @return int|null
     */
    public function getMax()
    {
        return $this->max;
    }


    /**
     * @param ValidatedValue $validatedValue
     * @return ValidatedValue
     */
    public function validate($validatedValue)
    {
        $validatedValue = parent::validate($validatedValue);

        if(!$validatedValue->isValid())
            return $validatedValue;

        $error = false;

        if(!$validatedValue->isNull()){

            $val = $validatedValue->getValue();

            if($this->min !== null && $val < $this->min)
                $error = true;

            if($this->max !== null && $val > $this->max)
                $error = true;

        }

        if($error){
            return new ValidatedValue(false, true, $validatedValue->getValue());
        }else{
            return $validatedValue;
        }

    }

    /**
     * @return AttrExt_Validate_Int
     */
    public static function of(){
        return new AttrExt_Validate_Int();
    }

}

