<?php

namespace monolitum\backend\res;

interface HrefResolver
{

    /**
     * @return string
     */
    function resolve();

    /**
     * @return array<string, string>
     */
    function getParamsAlone();

}