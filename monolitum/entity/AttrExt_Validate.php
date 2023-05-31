<?php

namespace monolitum\entity;

class AttrExt_Validate extends AttrExt
{

    private $nullable = false;

    /**
     * @param bool $nullable
     * @return $this
     */
    public function setNullable($nullable)
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
     * @param ValidatedValue $value
     */
    public function validate($value){
        $isStringEmpty = is_string($value) && strlen(trim($value)) == 0;
        if(!$this->nullable && (
            $value == null || $isStringEmpty
        ))
            return new ValidatedValue(false);
        if($this->nullable && $isStringEmpty)
            return new ValidatedValue(true, null);
    }

    /**
     * @return AttrExt_Validate
     */
    static function of(){
        return new AttrExt_Validate();
    }

}