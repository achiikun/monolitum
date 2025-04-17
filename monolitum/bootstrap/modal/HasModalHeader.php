<?php

namespace monolitum\bootstrap\modal;

use monolitum\core\util\ListUtils;
use monolitum\core\Renderable_Node;
use monolitum\frontend\html\HtmlElement;

trait HasModalHeader
{
    use HasModalTitle;

    private $headerElements = [];

    public function appendHeader($active, $idx = null)
    {
        ListUtils::insertAnElementIntoAnArray($this->headerElements, $this->buildRenderable($active), $idx);
        return $this;
    }

    abstract public function buildRenderable($active);

    private function createModalHeaderElement()
    {
        if(count($this->headerElements) > 0 || $this->title !== null){

            $modalHeader = new HtmlElement("div");
            $modalHeader->addClass("modal-header");

            if($this->title !== null){

                $modalTitle = new HtmlElement("h1");
                $modalTitle->addClass("modal-title", "fs-5");
                $modalTitle->setContent($this->title);

                $modalHeader->addChildElement($modalTitle);
            }

            Renderable_Node::renderRenderedTo(Renderable_Node::renderArray($this->headerElements), $modalHeader);

            return $modalHeader;
        }

        return null;
    }

}
