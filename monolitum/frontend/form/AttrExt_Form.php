<?php
namespace monolitum\frontend\form;

use monolitum\entity\AttrExt;
use monolitum\entity\ValidatedValue;

class AttrExt_Form extends AttrExt
{

    /**
     * @var string
     */
    private $label;

    /**
     * @var bool
     */
    private $nullable = false;

    /**
     * @var string|null
     */
    private $nullLabel;

    private $isDefaultSet = false;
    private $def = null;
    private $substituteNotValid = false;

    /**
     * @param string $label
     * @return $this
     */
    function label($label) {
        $this->label = $label;
        return $this;
    }

    /**
     * @param bool $nullable
     */
    public function nullable($nullLabel=null)
    {
        $this->nullable = true;
        $this->nullLabel = $nullLabel;
        return $this;
    }

    /**
     * @param mixed $value
     * @param bool $substituteNotValid
     * @return $this
     */
    public function def($value, $substituteNotValid = false)
    {
        $this->isDefaultSet = true;
        $this->def = $value;
        $this->substituteNotValid = $substituteNotValid;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return bool
     */
    public function isNullable()
    {
        return $this->nullable;
    }

    /**
     * @return string|null
     */
    public function getNullLabel()
    {
        return $this->nullLabel;
    }

    public function revalidate(ValidatedValue $validated)
    {
        if(!$validated->isValid())
            return $this->makeDefault($validated);
        if(!$this->isNullable() && $validated->isNull())
            return new ValidatedValue(false, $validated->getValue());
        return $validated;
    }

    protected function makeDefault(ValidatedValue $validated)
    {
        if(!$this->isDefaultSet)
            return $validated;

        $isValid = $validated->isValid();
        $isNull = $validated->isNull();

        if($isValid){
            if($isNull)
                return new ValidatedValue(true, $this->def);
        }else{
            if($this->substituteNotValid)
                return new ValidatedValue(true, $this->def);
        }
        return $validated;
    }

    public static function of(){
        return new AttrExt_Form();
    }

}

