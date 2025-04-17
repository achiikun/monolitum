<?php

namespace monolitum\bootstrap\modal;

use monolitum\core\util\ListUtils;
use monolitum\core\Renderable_Node;
use monolitum\frontend\html\HtmlElement;

trait HasModalFooter
{
    private $footerElements = [];

    public function appendFooter($active, $idx = null)
    {
        ListUtils::insertAnElementIntoAnArray($this->footerElements, $this->buildRenderable($active), $idx);
        return $this;
    }

    abstract public function buildRenderable($active);

    private function createModalFooterElement(){

        if(count($this->footerElements) > 0){

            $modalFooter = new HtmlElement("div");
            $modalFooter->addClass("modal-footer");

            Renderable_Node::renderRenderedTo(Renderable_Node::renderArray($this->footerElements), $modalFooter);

            return $modalFooter;
        }

        return null;
    }

}
