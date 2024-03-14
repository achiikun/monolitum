<?php

namespace monolitum\entity;

use monolitum\core\ts\TS;

class AttrExt_Validate extends AttrExt
{

    private $nullable = true;

    /**
     * @var string|TS
     */
    private $nullableError;

//    private $isDefaultSet = false;
//    private $def = null;
//    private $substituteNotValid = false;

    /**
     * @param string|TS $nullableError
     * @return $this
     */
    public function nonNullable($nullableError = null)
    {
        $this->nullable = false;
        $this->nullableError = $nullableError;
        return $this;
    }

    /**
     * @param bool $nullable
     * @return $this
     */
    public function nullable($nullable = true)
    {
        $this->nullable = $nullable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNullable()
    {
        return $this->nullable;
    }

    /**
     * @param ValidatedValue $validatedValue
     * @return ValidatedValue
     */
    public function validate($validatedValue){

        if(!$this->isNullable() && $validatedValue->isNull())
            return new ValidatedValue(false, true, $validatedValue->getValue(), $this->nullableError);

        return $validatedValue;

    }

    /**
     * @return AttrExt_Validate
     */
    static function of(){
        return new AttrExt_Validate();
    }

}