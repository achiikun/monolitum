<?php

namespace  monolitum\database;

class Query_GT extends Query_CMP
{

    public function __construct($string)
    {
        parent::__construct($string, ">");
    }

}
