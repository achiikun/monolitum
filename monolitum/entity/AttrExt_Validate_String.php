<?php
namespace monolitum\entity;

use monolitum\core\panic\DevPanic;
use monolitum\core\ts\TS;

class AttrExt_Validate_String extends AttrExt_Validate
{

    /**
     * @var string
     */
    private $regex;

    /**
     * @var string|TS
     */
    private $regexError;

    /**
     * @var string[]|TS[]
     */
    private $enums;

    /**
     * @var string|TS
     */
    private $enumsError;

    /**
     * @var int
     */
    private $filterValidate;

    /**
     * @var string|TS
     */
    private $filterValidateError;

    /**
     * @var int|null
     */
    private $maxChars = null;

    /**
     * @var string|TS
     */
    private $maxCharsError;

    /**
     * @var \Closure
     */
    private $validatorFunction = null;

    /**
     * @var string|TS
     */
    private $validatorFunctionError;

    /**
     * @var \Closure
     */
    private $postprocessorFunction = null;

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
     * @param string|TS $maxCharsError
     * @return $this
     */
    public function maxChars($maxChars, $maxCharsError = null)
    {
        $this->maxChars = $maxChars;
        $this->maxCharsError = $maxCharsError;
        return $this;
    }

    /**
     * Set a regular expression to validate the string.
     * It must follow the following pattern "/^...$/"
     * @param string $regex
     * @param string|TS $regexError
     * @return $this
     */
    public function regex($regex, $regexError = null)
    {
        $this->regex = $regex;
        $this->regexError = $regexError;
        return $this;
    }

    /**
     * @param string[]|TS[] $strings
     * @param string|TS $enumsError
     * @return $this
     */
    public function enum($strings, $enumsError = null)
    {
        $this->enums = $strings;
        $this->enumsError = $enumsError;
        return $this;
    }

    /**
     * @param int $filterValidate
     * @param string|TS $filterValidateError
     * @return $this
     */
    public function filter_validate($filterValidate, $filterValidateError = null){
        $this->filterValidate = $filterValidate;
        $this->filterValidateError = $filterValidateError;
        return $this;
    }

    /**
     * @param \Closure $validatorFunction
     * @param string|TS $validatorFunctionError
     * @return $this
     */
    public function func_validator(\Closure $validatorFunction, $validatorFunctionError = null)
    {
        $this->validatorFunction = $validatorFunction;
        $this->validatorFunctionError = $validatorFunctionError;
        return $this;
    }

    /**
     * @param \Closure $postprocessorFunction
     * @return $this
     */
    public function func_postprocessor(\Closure $postprocessorFunction)
    {
        $this->postprocessorFunction = $postprocessorFunction;
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
        $errorMessage = null;

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
                    $errorMessage = $this->enumsError;
                }
            }
            if(!$error && $this->maxChars !== null){
                if(strlen($validatedValue->getValue()) > $this->maxChars) {
                    $error = true;
                    $errorMessage = $this->maxCharsError;
                }
            }
            if(!$error && $this->regex !== null){
                if(!preg_match($this->regex, $validatedValue->getValue())) {
                    $error = true;
                    $errorMessage = $this->regexError;
                }
            }
            if(!$error && $this->filterValidate !== null){
                if(!filter_var($validatedValue->getValue(), $this->filterValidate)) {
                    $error = true;
                    $errorMessage = $this->filterValidateError;
                }
            }
            if(!$error && $this->validatorFunction !== null){
                $vf = $this->validatorFunction;
                $result = $vf($validatedValue->getValue());
                if(!$result){
                    $error = true;
                    $errorMessage = $this->validatorFunctionError;
                }
            }
        }

        if($error){
            return new ValidatedValue(false, true, $validatedValue->getValue(), $errorMessage);
        }else{

            if($this->postprocessorFunction !== null){
                $vf = $this->postprocessorFunction;
                $result = $vf($validatedValue->getValue());
                return new ValidatedValue($validatedValue->isValid(), $validatedValue->isWellFormat(), $result, $validatedValue->getError());
            }

            return $validatedValue;
        }
    }

    /**
     * @return AttrExt_Validate_String
     */
    public static function from(){
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

    /**
     * @param $value
     * @return TS|string|null
     */
    public function getEnumString($value)
    {
        if($this->enums !== null){
            foreach ($this->enums as $enumKey => $enumValue){
                if(is_string($enumKey)){
                    if($value == $enumKey){
                        return $enumValue;
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

