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

    /**
     * @var callable|null
     */
    private $applyer;

    /**
     * @param $builder callable|null
     * @param $applyer callable|null
     */
    function __construct($builder = null, $applyer = null)
    {
        parent::__construct($builder);
        $this->applyer = $applyer;
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

    /**
     * @param string $classes
     * @return $this
     */
    public function addClass(...$classes) {
        $this->elementComponent->addClass(...$classes);
        return $this;
    }

    /**
     * Sets a class with an alias, if this class is reset with the same alias, the previous class is removed
     * @param string $alias
     * @param string $class
     * @return $this
     */
    public function setClass($alias, $class = null)
    {
        $this->elementComponent->setClass($alias, $class);
        return $this;
    }

    function onNotReceived()
    {

    }

    public function apply()
    {
        if($this->applyer !== null){
            $b = $this->applyer;
            $b($this);
        }
    }
}