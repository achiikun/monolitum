<?php

namespace monolitum\frontend\component;


use monolitum\core\Active;
use monolitum\core\Renderable_Node;
use monolitum\core\panic\DevPanic;

abstract class Body extends Renderable_Node implements Active {

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    public function onNotReceived()
    {
        throw new DevPanic();
    }

}
