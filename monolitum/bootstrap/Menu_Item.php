<?php

namespace monolitum\bootstrap;

use monolitum\backend\params\Link;
use monolitum\backend\params\Path;
use monolitum\backend\res\Active_Create_HrefResolver;
use monolitum\core\Find;
use monolitum\frontend\Component;
use monolitum\frontend\component\A;
use monolitum\frontend\component\Li;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\Rendered;

class Menu_Item extends Li
{

    /**
     * @var string
     */
    protected $text;

    /**
     * @var Link|Path
     */
    private $link;

    /**
     * @var bool
     */
    protected $active = false;

    /**
     * @var bool
     */
    protected $disabled = false;

    /**
     * @var A
     */
    private $a;

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return $this;
     */
    public function text($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return Link|Path
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param Link|Path $link
     * @return $this;
     */
    public function link($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return $this
     */
    public function active($active = true)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     * @return $this
     */
    public function disabled($disabled = true)
    {
        $this->disabled = $disabled;
        return $this;
    }

    protected function afterBuildNode()
    {
        /** @var Menu_Item_Holder $menuItemHolder */
        $menuItemHolder = Find::sync(Menu_Item_Holder::class);
        if(!$menuItemHolder->isNav()){
            $this->addClass("nav-item");
        }

        $this->a = A::add(function (A $it) use ($menuItemHolder) {
            // TODO this can be wrong if there is portals or references
            if($menuItemHolder->isNav()){
                $it->addClass("nav-link");
            }else {
                // In a dropdown
                $it->addClass("dropdown-item");
            }

            if($this->active){
                $it->addClass("active");
            }
            if($this->disabled){
                $it->addClass("disabled");
            }else{
                $it->setHref($this->link);
            }
            $it->setContent($this->text);

        });

    }

    /**
     * @param $content
     * @return Menu_Item
     */
    public static function of($content){
        $item = new Menu_Item();
        $item->text($content);
        return $item;
    }

}