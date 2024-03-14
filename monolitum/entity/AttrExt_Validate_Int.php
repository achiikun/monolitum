<?php
namespace monolitum\entity;

use http\Params;
use monolitum\core\panic\DevPanic;
use monolitum\core\ts\TS;
use monolitum\frontend\form\AttrExt_Form_String;

class AttrExt_Validate_Int extends AttrExt_Validate
{

    private $min = null;

    /**
     * @var string|TS
     */
    private $minError = null;

    private $max = null;

    /**
     * @var string|TS
     */
    private $maxError = null;

    /**
     * @param int $int
     * @param string|TS $minError
     * @return $this
     */
    public function min($int, $minError = null)
    {
        $this->min = $int;
        $this->minError = $minError;
        return $this;
    }

    /**
     * @param int $int
     * @param string|TS $maxError
     * @return $this
     */
    public function max($int, $maxError = null)
    {
        $this->max = $int;
        $this->maxError = $maxError;
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
        $errorMessage = null;

        if(!$validatedValue->isNull()){

            $val = $validatedValue->getValue();

            if($this->min !== null && $val < $this->min){
                $error = true;
                $errorMessage = $this->minError;
            }

            if($this->max !== null && $val > $this->max){
                $error = true;
                $errorMessage = $this->maxError;
            }

        }

        if($error){
            return new ValidatedValue(false, true, $validatedValue->getValue(), $errorMessage);
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

