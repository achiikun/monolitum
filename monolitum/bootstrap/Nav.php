<?php

namespace monolitum\bootstrap;

use monolitum\backend\res\Active_Create_HrefResolver;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;

class Nav extends ElementComponent implements Menu_Item_Holder
{

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

    public function isNav()
    {
        return true;
    }

    public function openToLeft()
    {
        return false;
    }

    public function isSubmenu()
    {
        return false;
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

    protected function afterBuildNode()
    {

        if($this->type)
            $this->addClass("nav-" . $this->type);

        if($this->vertical)
            $this->addClass("flex-column");

        if($this->fill)
            $this->addClass("nav-fill");

        parent::afterBuildNode();
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