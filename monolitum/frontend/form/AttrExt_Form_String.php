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
     * @var bool
     */
    private $html;

    /**
     * @var bool
     */
    private $searchable = false;

    /**
     * @var string
     */
    private $inputType;

    /**
     * @return $this
     */
    public function password() {
        $this->password = true;
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
     * @param bool $searchable
     * @return $this
     */
    public function searchable($searchable=true)
    {
        $this->searchable = $searchable;
        return $this;
    }

    /**
     * @param $string string
     * @return $this
     */
    public function inputType($string)
    {
        $this->inputType = $string;
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

    /**
     * @return bool
     */
    public function isSearchable()
    {
        return $this->searchable;
    }

    /**
     * @return string
     */
    public function getInputType()
    {
        return $this->inputType;
    }

    static function from(){
        return new AttrExt_Form_String();
    }


}

