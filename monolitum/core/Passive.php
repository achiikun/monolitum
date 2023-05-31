<?php

namespace monolitum\core;

interface Passive{

    /**
     * @param Active $active
     * @return Active
     */
    function _receive($active);

}