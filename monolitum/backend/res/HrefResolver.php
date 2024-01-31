<?php

namespace monolitum\backend\res;

interface HrefResolver
{

    /**
     * @return string
     */
    function resolve();

    /**
     * If the user requested to get the params alone, this method will return them.
     * @return array<string, string>|null
     */
    function getParamsAlone();

}