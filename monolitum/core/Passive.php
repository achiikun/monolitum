<?php

namespace monolitum\core;

interface Passive{

    /**
     * @param $active Active
     * @param $currentDepth int
     * @return Active
     */
    function _receive($active, $currentDepth);

}