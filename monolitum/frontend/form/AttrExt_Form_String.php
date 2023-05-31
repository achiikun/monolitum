<?php
namespace monolitum\frontend\form;

use monolitum\core\panic\DevPanic;
use monolitum\entity\ValidatedValue;

class AttrExt_Form_String extends AttrExt_Form
{

    /**
     * @var bool
     */
    private $password;

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
     * @var bool
     */
    private $html;

    function password() {
        $this->password = true;
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
     * @param bool $html
     * @return $this
     */
    public function html($html=true)
    {
        $this->html = $html;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPassword()
    {
        return $this->password;
    }

    /**
     * @return bool
     */
    public function isHtml()
    {
        return $this->html;
    }

    public function revalidate(ValidatedValue $validated)
    {
        $error = false;

        if(!$validated->isValid())
            return $this->makeDefault($validated);
        if(!$this->isNullable() && $validated->isNull())
            $error = true;
        else if(!$validated->isNull()){
            if($this->enums !== null){
                $found = false;
                foreach ($this->enums as $enumKey => $enumValue){
                    if(is_string($enumKey)){
                        if($validated->getValue() == $enumKey){
                            $found = true;
                            break;
                        }
                    }else if(is_string($enumValue)){
                        if($validated->getValue() == $enumValue){
                            $found = true;
                            break;
                        }
                    }else if(is_array($enumValue)){
                        if($validated->getValue() == $enumValue[0]){
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
            if($this->regex !== null){
                if(!preg_match($this->regex, $validated->getValue()))
                    $error = true;
            }
            if($this->filter_validate !== null){
                if(!filter_var($validated->getValue(), $this->filter_validate))
                    $error = true;
            }
        }

        if($error){
            return $this->makeDefault(new ValidatedValue(false, $validated->getValue()));
        }else{
            return $validated;
        }
    }

    static function of(){
        return new AttrExt_Form_String();
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

}

