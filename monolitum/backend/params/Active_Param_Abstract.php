<?php

namespace monolitum\backend\params;

use monolitum\core\Active;
use monolitum\entity\ValidatedValue;

class Active_Param_Abstract implements Active, Param
{
    const TYPE_STRING = "str";
    const TYPE_INT = "int";

    /**
     * @var string
     */
    private $type;

    /**
     * @var ValidatedValue
     */
    private $validatedValue;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param ValidatedValue $validatedValue
     */
    public function setValidatedValue($validatedValue)
    {
        $this->validatedValue = $validatedValue;
    }

    /**
     * @return ValidatedValue
     */
    public function getValidatedValue()
    {
        return $this->validatedValue;
    }

    function onNotReceived()
    {
        $this->validatedValue = new ValidatedValue(false); // TODO default parameter
    }
}