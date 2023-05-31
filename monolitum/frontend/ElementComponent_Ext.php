<?php

namespace monolitum\frontend;

use monolitum\core\Active;
use monolitum\core\Node;

class ElementComponent_Ext extends Node implements Active
{

    /**
     * @var ElementComponent
     */
    private $elementComponent;

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }


    /**
     * @param ElementComponent $elementComponent
     */
    function _setElementComponent($elementComponent){
        $this->elementComponent = $elementComponent;
    }

    /**
     * @return ElementComponent
     */
    public function getElementComponent()
    {
        return $this->elementComponent;
    }

    function onNotReceived()
    {

    }

    public function apply()
    {

    }
}