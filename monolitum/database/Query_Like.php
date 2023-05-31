<?php

namespace monolitum\database;

class Query_Like
{

    /**
     * @var string
     */
    private $string;

    /**
     * @var array<string>
     */
    private $params = [];

    /**
     * @param string $string
     * @param array $params
     */
    public function __construct($string, ...$params)
    {
        $this->string = $string;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @return array|string[]
     */
    public function getParams()
    {
        return $this->params;
    }


}