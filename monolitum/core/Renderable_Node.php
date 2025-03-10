<?php

namespace monolitum\core;

use monolitum\backend\router\Router_Panic;
use monolitum\core\panic\DevPanic;
use monolitum\frontend\component\Head;
use monolitum\frontend\Rendered;

abstract class Renderable_Node extends Node implements Active {

    /**
     * @var null|Renderable_Node|Renderable|array<Renderable_Node|Renderable>
     */
    private $childs = null;

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    public static function isAppendableRenderableNode(Active $active)
    {
        return $active instanceof Renderable_Node
            && !($active instanceof Head)
            && !($active instanceof Router_Panic);
    }

    /**
     * @param mixed $element
     * @param int|null $idx
     * @return void
     */
    protected function insertIntoArray($element, $idx = null){
        if($idx !== null){
            if($this->childs === null){
                $this->childs = $element;
            }else{
                if(!is_array($this->childs))
                    $this->childs = [$this->childs];
                array_splice($this->childs, $idx, 0, [$element]);
            }
        }else{
            if($this->childs === null){
                $this->childs = $element;
            }else if(!is_array($this->childs)) {
                $this->childs = [$this->childs, $element];
            }else{
                $this->childs[] = $element;
            }
        }
    }

    /**
     * @param Renderable_Node|Renderable $active
     * @param int|null $idx
     * @return $this
     */
    public function append($active, $idx=null)
    {
        if ($active instanceof Renderable_Node) {
            if ($active->isBuilt() && $active->getParent() !== $this)
                throw new DevPanic("Component has a parent.");
            if ($this->isBuilding())
                $this->insertIntoArray($this->buildChild($active), $idx);
            else
                $this->insertIntoArray($active, $idx);
        }else if(is_array($active)){
            $this->insertIntoArray(Rendered::of($active), $idx);
        }else {
            $this->insertIntoArray($active, $idx);
        }
        return $this;
    }

    protected function receiveActive($active)
    {
        if(Renderable_Node::isAppendableRenderableNode($active)){
            $this->append($active);
            return true;
        }
        return parent::receiveActive($active); // TODO: Change the autogenerated stub
    }

    protected function buildNode()
    {
        if(is_array($this->childs)){
            foreach ($this->childs as $child) {
                if($child instanceof Node)
                    $this->buildChild($child);
            }
        }else {
            if($this->childs instanceof Node)
                $this->buildChild($this->childs);
        }
        parent::buildNode(); // TODO: Change the autogenerated stub
    }

    protected function executeNode()
    {
        if(is_array($this->childs)){
            foreach ($this->childs as $child) {
                if($child instanceof Node)
                    $this->executeChild($child);
            }
        }else{
            if($this->childs instanceof Node)
                $this->executeChild($this->childs);
        }
        parent::executeNode(); // TODO: Change the autogenerated stub
    }

    function onNotReceived()
    {
        // TODO: Implement onNotReceived() method.
    }

    /**
     * @return Renderable|array|null
     */
    public function render()
    {
        return $this->renderChilds();
    }

    /**
     * @return Renderable|array|null
     */
    public function renderChilds()
    {
        if(is_array($this->childs)){
            $rendered = [];
            foreach ($this->childs as $child) {
                if($child instanceof Renderable_Node)
                    $rendered[] = $child->render();
                else if($child instanceof Renderable)
                    $rendered[] = $child;
            }
            return $rendered;
        }else{
            if($this->childs instanceof Renderable_Node)
                return $this->childs->render();
            else if($this->childs instanceof Renderable)
                return $this->childs;
        }
        return null;

    }

    /**
     * @param array|Renderable_Node|Renderable $rendered
     * @param mixed $element
     * @return void
     */
    public static function renderRenderedTo($rendered, $element)
    {

        if(is_array($rendered)){
            foreach ($rendered as $rendered2) {
                Renderable_Node::renderRenderedTo($rendered2, $element);
            }
        }else if($rendered instanceof Renderable_Node){
            Renderable_Node::renderRenderedTo($rendered->render(), $element);
        }else if($rendered instanceof Renderable) {
            $rendered->renderTo($element);
        }

    }


}