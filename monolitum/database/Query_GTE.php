<?php

namespace  monolitum\database;

class Query_GTE extends Query_CMP
{

    public function __construct($string)
    {
        parent::__construct($string, ">=");
    }

}
