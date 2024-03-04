<?php
namespace monolitum\entity;

use http\Params;
use monolitum\core\panic\DevPanic;
use monolitum\frontend\form\AttrExt_Form_String;

class AttrExt_Validate_String extends AttrExt_Validate
{

    /**
     * @var string
     */
    private $regex;

    /**
     * @var string[]
     */
    private $enums;

    /**
     * @var int
     */
    private $filter_validate;

    /**
     * @var int|null
     */
    private $maxChars = null;

    /**
     * @var bool
     */
    private $trim;

    /**
     * @return $this
     */
    public function trim()
    {
        $this->trim = true;
        return $this;
    }

    /**
     * @param int $maxChars
     * @return $this
     */
    public function maxChars($maxChars)
    {
        $this->maxChars = $maxChars;
        return $this;
    }

    /**
     * @param string $string
     * @return $this
     */
    public function regex($string)
    {
        $this->regex = $string;
        return $this;
    }

    /**
     * @param array<string>|array<array<string>> $strings
     * @return $this
     */
    public function enum($strings)
    {
        $this->enums = $strings;
        return $this;
    }

    /**
     * @param int $filter_validate
     * @return $this
     */
    public function filter_validate($filter_validate){
        $this->filter_validate = $filter_validate;
        return $this;
    }

    /**
     * @param ValidatedValue $validatedValue
     * @return ValidatedValue
     */
    public function validate($validatedValue)
    {
        // Transform the value before validating
        if($validatedValue->isWellFormat() && $this->trim){
            $value = $validatedValue->getValue();
            $validatedValue = new ValidatedValue(
                $validatedValue->isValid(),
                $validatedValue->isWellFormat(),
                is_string($value) ? trim($value) : $value
            );
        }

        $validatedValue = parent::validate($validatedValue);

        if(!$validatedValue->isValid())
            return $validatedValue;

        $error = false;

        if(!$validatedValue->isNull()){
            if($this->enums !== null){
                $found = false;
                foreach ($this->enums as $enumKey => $enumValue){
                    if(is_string($enumKey)){
                        if($validatedValue->getValue() == $enumKey){
                            $found = true;
                            break;
                        }
                    }else if(is_string($enumValue)){
                        if($validatedValue->getValue() == $enumValue){
                            $found = true;
                            break;
                        }
                    }else if(is_array($enumValue)){
                        if($validatedValue->getValue() == $enumValue[0]){
                            $found = true;
                            break;
                        }
                    }else{
                        throw new DevPanic("Enum constant not found");
                    }
                }
                if(!$found){
                    $error = true;
                }
            }
            if($this->maxChars !== null){
                if(strlen($validatedValue->getValue()) > $this->maxChars)
                    $error = true;
            }
            if($this->regex !== null){
                if(!preg_match($this->regex, $validatedValue->getValue()))
                    $error = true;
            }
            if($this->filter_validate !== null){
                if(!filter_var($validatedValue->getValue(), $this->filter_validate))
                    $error = true;
            }
        }

        if($error){
            return new ValidatedValue(false, true, $validatedValue->getValue());
        }else{
            return $validatedValue;
        }
    }

    /**
     * @return AttrExt_Validate_String
     */
    public static function of(){
        return new AttrExt_Validate_String();
    }

    public function hasEnum()
    {
        return $this->enums != null;
    }

    /**
     * @return string[]
     */
    public function getEnums()
    {
        return $this->enums;
    }

    /**
     * @return int|null
     */
    public function getMaxChars()
    {
        return $this->maxChars;
    }

    public function getEnumString($value)
    {
        if($this->enums !== null){
            foreach ($this->enums as $enumKey => $enumValue){
                if(is_string($enumKey)){
                    if($value == $enumKey){
                        if(is_array($enumValue)){
                            return $enumValue[0];
                        }else{
                            return $enumValue;
                        }
                    }
                }else if(is_string($enumValue)){
                    if($value == $enumValue){
                        return $enumValue;
                    }
                }else if(is_array($enumValue)){
                    if($value == $enumValue[0]){
                        return  $enumValue[1];
                    }
                }else{
                    throw new DevPanic("Enum constant not found");
                }
            }
        }

        return null;
    }

}

