<?php

namespace  monolitum\database;

class Query_LTE extends Query_CMP
{
    public function __construct($string)
    {
        parent::__construct($string, "<=");
    }
}
