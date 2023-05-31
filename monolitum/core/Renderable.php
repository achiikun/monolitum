<?php

namespace monolitum\core;

interface Renderable
{

    /**
     * @param mixed $element
     */
    function renderTo($element);
}