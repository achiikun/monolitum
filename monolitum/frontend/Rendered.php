<?php

namespace monolitum\frontend;

use monolitum\core\Renderable_Node;
use monolitum\core\Renderable;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\html\HtmlElementContent;

class Rendered implements Renderable {

    /**
     * @var HtmlElement|HtmlElementContent|null
     */
    private $single = null;
    
    /**
     * @var array<HtmlElement|HtmlElementContent>
     */
    private $multiple = null;

    /**
     * @param array|HtmlElement|HtmlElementContent|Component|Rendered|string $element
     * @return Rendered
     */
    static function of($element){
        $r = new Rendered();
        if($element instanceof HtmlElement){
            $r->single = $element;
        }else if($element instanceof HtmlElementContent){
            $r->single = $element;
        }else if(is_string($element)){
            $r->single = new HtmlElementContent($element);
        }else if($element instanceof Renderable_Node){
            $r->mergeWith($element->render());
        }else if($element instanceof Rendered){
            $r->mergeWith($element);
        }else if(is_array($element)){
            foreach ($element as $element2){
                if($element2 instanceof HtmlElement){
                    $r->mergeWith($element2);
                }else if($element2 instanceof HtmlElementContent){
                    $r->mergeWith($element2);
                }else if(is_string($element)){
                    $r->mergeWith(new HtmlElementContent($element));
                }else if($element2 instanceof Renderable_Node){
                    $r->mergeWith($element2->render());
                }else if($element2 instanceof Rendered){
                    $r->mergeWith($element2);
                }
            }
        }
        return $r;
    }

    /**
     * @return Rendered
     */
    static function ofEmpty(){
        return new Rendered();
    }

    /**
     * @param Rendered|HtmlElement|HtmlElementContent $renderedComponent
     */
    public function mergeWith($renderedComponent){
        if($renderedComponent === null)
            return;

        $element = null;
        if(
            $renderedComponent instanceof HtmlElement ||
            $renderedComponent instanceof HtmlElementContent ||
            ($element = $renderedComponent->single) != null
        ){
            if($element == null)
                $element = $renderedComponent;

            if($this->multiple == null){
                if($this->single == null){
                    $this->single = $element;
                }else{
                    $this->multiple = [$this->single, $element];
                    $this->single = null;
                }
            }else{
                $this->multiple[] = $element;
            }
        }else if($renderedComponent->multiple != null){
            if($this->multiple == null) {
                if($this->single != null){
                    $this->multiple = [$this->single];
                    $this->single = null;
                }else{
                    $this->multiple = [];
                }
            }
            foreach ($renderedComponent->multiple as $single) {
                $this->multiple[] = $single;
            }
        }
    }

    /**
     * @param HtmlElement $element
     */
    function renderTo($element){
        if($this->single != null){
            if($this->single instanceof HtmlElement)
                $element->addChildElement($this->single);
            else
                $element->addContent($this->single->getContent());
        } else if($this->multiple != null){
            foreach ($this->multiple as $single){

                if($single instanceof HtmlElement)
                    $element->addChildElement($single);
                else
                    $element->addContent($single->getContent());

            }
        }
    }
    
    
}