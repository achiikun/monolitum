<?php
namespace monolitum\frontend\form;

use monolitum\entity\AttrExt;
use monolitum\entity\ValidatedValue;

class AttrExt_Form extends AttrExt
{

    /**
     * @var string | string[]
     */
    private $label;

    /**
     * @var string|string[]|null
     */
    private $nullLabel;

    private $isDefaultSet = false;
    private $def = null;
    private $substituteNotValid = false;

    /**
     * @param string|string[] $label
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
     * @return string
     */
    public function getLabel($language=null)
    {
        if(is_array($this->label)){
            if($language != null){
                return $this->label[$language];
            }else{
                foreach ($this->label as $l){
                    return $l;
                }
            }
        }else{
            return $this->label;
        }
    }

    /**
     * @return string|null
     */
    public function getNullLabel($language=null)
    {
        if(is_array($this->nullLabel)){
            if($language != null){
                return $this->nullLabel[$language];
            }else{
                foreach ($this->nullLabel as $l){
                    return $l;
                }
            }
        }else{
            return $this->nullLabel;
        }
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

