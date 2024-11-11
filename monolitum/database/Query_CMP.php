<?php

namespace monolitum\database;

class Query_CMP
{

    /**
     * @var numeric|DateTime
     */
    private $value;
    /**
     * @var string
     */
    private $sign;

    /**
     * @param numeric|DateTime $string
     * @param string $sign
     */
    protected function __construct($string, $sign)
    {
        $this->value = $string;
        $this->sign = $sign;
    }

    /**
     * @return numeric|DateTime
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getSign()
    {
        return $this->sign;
    }

}
