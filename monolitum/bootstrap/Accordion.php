<?php

namespace monolitum\bootstrap;

use monolitum\backend\globals\Active_NewId;
use monolitum\core\GlobalContext;
use monolitum\frontend\component\Div;
use monolitum\frontend\component\H;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;

class Accordion extends ElementComponent
{

    /** @var array<Accordion_Item> */
    private $items = [];

    private $builtHeaders = [];
    private $builtBodies = [];


    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement("div"), $builder);
        $this->addClass("accordion");
    }

    /**
     * @param Accordion_Item $item
     */
    public function addItem($item)
    {
        $this->items[] = $item;
        return $this;
    }

    protected function afterBuildNode()
    {

        $id = Active_NewId::go_newId();
        $this->setId($id);

        foreach ($this->items as $item){

            $idItem = Active_NewId::go_newId();

            $divItem = new Div();
            $divItem->addClass("accordion-item");

            $h2 = new H(2);
            $h2->addClass("accordion-header");

            $button = new ElementComponent(new HtmlElement("button"));
            $button->addClass("accordion-button");
            if($item->isCollapsed())
                $button->addClass("collapsed");
            $button->setAttribute("data-bs-toggle", "collapse");
            $button->setAttribute("data-bs-target", "#" . $idItem);
            $button->push($item->getHeader()); // Already built

            $h2->append($button);
            $divItem->append($h2);

            $divCollapse = new Div();
            $divCollapse->setId($idItem);
            $divCollapse->addClass("accordion-collapse", "collapse");
            if(!$item->isCollapsed())
                $divCollapse->addClass("show");
            $divCollapse->setAttribute("data-bs-parent", "#" . $id);

            $divBody = new Div();
            $divBody->addClass("accordion-body");
            $divBody->append($item->getBody());

            $divCollapse->append($divBody);
            $divItem->append($divCollapse);

            $this->push($divItem);

        }

        parent::afterBuildNode();
    }

    protected function executeComponent()
    {

        parent::executeComponent();
    }

    /**
     * @param callable $builder
     * @return Accordion
     */
    public static function add($builder)
    {
        $fc = new Accordion($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}