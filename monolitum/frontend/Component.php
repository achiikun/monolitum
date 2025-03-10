<?php

namespace monolitum\frontend;

use monolitum\core\Active;
use monolitum\core\GlobalContext;
use monolitum\core\panic\DevPanic;
use monolitum\core\Renderable;
use monolitum\core\Renderable_Node;
use monolitum\core\ts\TS;
use monolitum\core\ts\TSLang;
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
     * @param Renderable_Node|Renderable|HtmlElement|HtmlElementContent|string|TS $active
     * @param int|null $idx
     * @return $this
     */
    public function append($active, $idx=null)
    {
        if ($active instanceof Renderable_Node) {
            parent::append($active);
        }else if(is_string($active) || $active instanceof TS){
            parent::append(new HtmlElementContent(TS::unwrap($active, TSLang::findWithOverwritten())), $idx);
        }else{
            parent::append($active, $idx);
        }
        return $this;
    }

    protected function receiveActive($active)
    {
        if(Renderable_Node::isAppendableRenderableNode($active)
            || $active instanceof HtmlElement){
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