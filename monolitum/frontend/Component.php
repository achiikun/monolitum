<?php

namespace monolitum\frontend;

use monolitum\backend\router\Router_Panic;
use monolitum\core\Active;
use monolitum\core\GlobalContext;
use monolitum\core\panic\DevPanic;
use monolitum\core\Renderable_Node;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\html\HtmlElementContent;

class Component extends Renderable_Node implements Active{

    /**
     * @param callable|null $builder
     */
    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    protected function buildNode()
    {
        parent::buildNode();
        $this->buildComponent();
    }

    /**
     * @param Renderable_Node|HtmlElement|HtmlElementContent|string $component
     * @return $this
     */
    public function append($component, $idx = null)
    {
        if ($component instanceof Renderable_Node) {
            parent::append($component);
        }else if(is_string($component)){
            parent::append(new HtmlElementContent($component), $idx);
        }else{
            parent::append($component, $idx);
        }
        return $this;
    }

    protected function receiveActive($active)
    {
        if($active instanceof Renderable_Node && !($active instanceof Router_Panic) || $active instanceof HtmlElement){
            $this->append($active);
            return true;
        }

        return parent::receiveActive($active); // HACK: parent Renderable_Node will never recieve a child
    }

    
    protected function buildComponent(){
        
    }

    public function onNotReceived()
    {
        throw new DevPanic();
    }

    protected function executeNode()
    {
        parent::executeNode(); // TODO: Change the autogenerated stub
        $this->executeComponent();
    }

    protected function executeComponent(){

    }

    /**
     * @param callable|null $builder
     * @return Component
     */
    public static function addEmpty($builder = null){
        $c = new Component($builder);
        GlobalContext::add($c);
        return $c;
    }

}