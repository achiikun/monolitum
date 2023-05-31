<?php
namespace monolitum\frontend\css;

class CSSAuto implements SizeAutoProperty
{

    function write()
    {
        return "auto";
    }
}