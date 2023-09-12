<?php

namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;

class Nav extends ElementComponent
{

    /**
     * @var array<Nav_Item>
     */
    private $items = [];

    /**
     * @var string
     */
    private $type = null;

    /**
     * @var bool
     */
    private $fill = false;

    /**
     * @var bool
     */
    private $vertical = false;

    public function __construct($builder)
    {
        parent::__construct(new HtmlElement("ul"), $builder);
        $this->addClass("nav");
    }

    /**
     * @param Nav_Item $leftItem
     * @return $this
     */
    public function addItem($leftItem)
    {
        $this->items[] = $leftItem;
        return $this;
    }

    /**
     * @return $this;
     */
    public function pills()
    {
        $this->type = "pills";
        return $this;
    }

    /**
     * @return $this;
     */
    public function tabs()
    {
        $this->type = "tabs";
        return $this;
    }

    /**
     * @return $this;
     */
    public function fill()
    {
        $this->fill = true;
        return $this;
    }

    /**
     * @return $this;
     */
    public function vertical()
    {
        $this->vertical = true;
        return $this;
    }

    protected function executeComponent()
    {

        if($this->type)
            $this->addClass("nav-" . $this->type);

        if($this->vertical)
            $this->addClass("flex-column");

        if($this->fill)
            $this->addClass("nav-fill");

        foreach ($this->items as $leftItem) {

            //<li class="nav-item">
            $li = new HtmlElement('li');
            $li->addClass("nav-item");

            //<a class="nav-link active" aria-current="page" href="#">Home</a>
            $a = new HtmlElement('a');
            $a->addClass("nav-link");
            if($leftItem->isActive()){
                $a->addClass("active");
            }
            if($leftItem->isDisabled()){
                $a->addClass("disabled");
            }else{
                $a->setAttribute("href", $leftItem->getLink(), false);
            }
            $a->setContent($leftItem->getText());

            $li->addContent($a);

            $this->push($li);
        }

        parent::executeComponent();
    }

    /**
     * @param callable $builder
     * @return Nav
     */
    public static function add($builder = null)
    {
        $fc = new Nav($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}