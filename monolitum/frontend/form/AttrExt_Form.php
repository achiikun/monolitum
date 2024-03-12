<?php
namespace monolitum\frontend\form;

use monolitum\core\ts\TS;
use monolitum\entity\AttrExt;

class AttrExt_Form extends AttrExt
{

    /**
     * @var string|TS
     */
    private $label;

    /**
     * @var string|TS|null
     */
    private $nullLabel;

    private $isDefaultSet = false;
    private $def = null;
    private $substituteNotValid = false;

    /**
     * @param string|TS $label
     * @return $this
     */
    function label($label) {
        $this->label = $label;
        return $this;
    }

    /**
     * @param string|string[] $nullLabel
     * @return $this
     */
    function nullLabel($nullLabel) {
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
     * @return string|TS
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string|TS|null
     */
    public function getNullLabel()
    {
        return $this->nullLabel;
    }

    /**
     * @return bool
     */
    public function isDefaultSet()
    {
        return $this->isDefaultSet;
    }

    /**
     * @return null
     */
    public function getDef()
    {
        return $this->def;
    }

    /**
     * @return bool
     */
    public function isSubstituteNotValid()
    {
        return $this->substituteNotValid;
    }

//    public function makeDefault(ValidatedValue $validated)
//    {
//        if(!$this->isDefaultSet)
//            return $validated;
//
//        $isValid = $validated->isValid();
//        $isNull = $validated->isNull();
//
//        if($isValid){
//            if($isNull)
//                return new ValidatedValue(true, true, $this->def);
//        }else{
//            if($this->substituteNotValid)
//                return new ValidatedValue(true, true, $this->def);
//        }
//        return $validated;
//    }

    public static function of(){
        return new AttrExt_Form();
    }

}

