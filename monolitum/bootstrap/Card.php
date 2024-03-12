<?php

namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\frontend\component\Div;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\Rendered;

class Card extends ElementComponent
{

    private $breakpoint;

    /**
     * @var Div
     */
    private $headerElement;

    /**
     * @var Div
     */
    private $footerElement;

    public function __construct($builder)
    {
        parent::__construct(new HtmlElement("div"), $builder);
    }

    protected function afterBuildNode()
    {
        $this->addClass("card");

        if($this->headerElement !== null){
            $this->buildChild($this->headerElement);
        }

        if($this->footerElement !== null){
            $this->buildChild($this->footerElement);
        }

        parent::afterBuildNode();
    }

    public function render()
    {
        if($this->headerElement !== null)
            Renderable_Node::renderRenderedTo($this->headerElement->render(), $this->getElement());
        Renderable_Node::renderRenderedTo(parent::renderChilds(), $this->getElement());
        if($this->footerElement !== null)
            Renderable_Node::renderRenderedTo($this->footerElement->render(), $this->getElement());
        return Rendered::of($this->getElement());
    }

    /**
     * @param HtmlElement|Renderable_Node|string $element
     * @return void
     */
    public function addHeader($element)
    {
        if($this->headerElement === null){
            $this->headerElement = new Div();
            $this->headerElement->addClass("card-header");
        }
        $this->headerElement->append($element);
    }

    /**
     * @return Div
     */
    public function getHeaderElement()
    {
        return $this->headerElement;
    }

    public function hasHeader()
    {
        return $this->headerElement !== null;
    }

    /**
     * @param HtmlElement|Renderable_Node|string $element
     * @return void
     */
    public function addFooter(...$element)
    {
        if($this->footerElement === null){
            $this->footerElement = new Div();
            $this->footerElement->addClass("card-footer");
        }
        $this->footerElement->append(...$element);
    }

    /**
     * @return Div
     */
    public function getFooterElement()
    {
        return $this->footerElement;
    }

    public function hasFooter()
    {
        return $this->footerElement !== null;
    }


    /**
     * @param callable $builder
     * @return Card
     */
    public static function add($builder)
    {
        $fc = new Card($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}