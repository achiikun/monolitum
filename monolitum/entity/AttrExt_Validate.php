<?php

namespace monolitum\entity;

class AttrExt_Validate extends AttrExt
{

    private $nullable = false;

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
            return new ValidatedValue(false, true, $validatedValue->getValue());

        return $validatedValue;

    }

    /**
     * @return AttrExt_Validate
     */
    static function of(){
        return new AttrExt_Validate();
    }

}