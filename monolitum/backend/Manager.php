<?php

namespace monolitum\backend;

use monolitum\core\Renderable_Node;

class Manager extends Renderable_Node
{

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    protected function buildNode()
    {
        $this->buildManager();
        parent::buildNode();
    }

    protected function buildManager(){

    }

    protected function executeNode()
    {
        parent::executeNode();
        $this->executeManager();
    }

    protected function executeManager(){

    }

}