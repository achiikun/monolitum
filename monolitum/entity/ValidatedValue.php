<?php

namespace monolitum\entity;

class ValidatedValue
{

    private $isValid;
    private $isWellFormat;
    private $value;

    private $error;

    /**
     * @var string|null
     */
    private $strValue;

    public function __construct($isValid=false, $wellFormat=false, $value = null, $error = null, $strValue = null)
    {
        $this->isValid = $isValid;
        $this->isWellFormat = $wellFormat;
        $this->value = $value;
        $this->error = $error;
        $this->strValue = $strValue;
    }

    /**
     * @return mixed
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string|null
     */
    public function getStrValue()
    {
        return $this->strValue;
    }

    /**
     * @return mixed|null
     */
    public function getError()
    {
        return $this->error;
    }

    public function isNull()
    {
        return $this->value === null;
    }

    public function isWellFormat()
    {
        return $this->isWellFormat;
    }

}
