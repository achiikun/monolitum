<?php

namespace monolitum\bootstrap;

use monolitum\frontend\component\Img;
use monolitum\backend\globals\Active_NewId;
use monolitum\backend\params\Link;
use monolitum\backend\params\Path;
use monolitum\bootstrap\style\BSStyle;
use monolitum\bootstrap\style\BSVerticalAlign;
use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\frontend\Component;
use monolitum\frontend\component\A;
use monolitum\frontend\component\Div;
use monolitum\frontend\component\Hr;
use monolitum\frontend\component\Li;
use monolitum\frontend\component\Span;
use monolitum\frontend\component\Ul;
use monolitum\frontend\css\CSSSize;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;

class NavBar extends ElementComponent
{

    /**
     * @var string
     */
    private $expandBreakpoint = BS::BREAKPOINT_LG;

    /**
     * @var Path|Link
     */
    private $brandLink = null;

    /**
     * @var Path|Component
     */
    private $brandIcon = null;

    /**
     * @var string|Component
     */
    private $brandTitle = null;

    /**
     * @var bool
     */
    private $themeDark = false;

    /**
     * @var array<Nav_Item>
     */
    private $leftItems = [];

    /**
     * @var string|ElementComponent
     */
    private $rightComponent;

    public function __construct($builder)
    {
        parent::__construct(new HtmlElement("div"), $builder);
        $this->addClass("navbar");
    }

    /**
     * @param string $expandBreakpoint
     * @return $this
     */
    public function setExpandBreakpoint($expandBreakpoint)
    {
        $this->expandBreakpoint = $expandBreakpoint;
        return $this;
    }

    /**
     * @return $this
     */
    public function setDark()
    {
        $this->themeDark = true;
        return $this;
    }

    /**
     * @param Link|Path|null $brandLink
     * @return $this
     */
    public function brandLink($brandLink)
    {
        $this->brandLink = $brandLink;
        return $this;
    }

    /**
     * @var string|Component $brandTitle
     * @return $this
     */
    public function brandTitle($brandTitle)
    {
        $this->brandTitle = $brandTitle;
        return $this;
    }

    /**
     * @param Component|Path|null $brandIcon
     */
    public function brandIcon($brandIcon)
    {
        $this->brandIcon = $brandIcon;
        return $this;
    }

    /**
     * @param Nav_Item $leftItem
     * @return $this
     */
    public function addLeft($leftItem)
    {
        $this->leftItems[] = $leftItem;
        return $this;
    }

    /**
     * @param Renderable_Node|string $rightItem
     * @return $this
     */
    public function addRight($rightItem)
    {
        $this->rightComponent = $rightItem;
        return $this;
    }

    /**
     * @param Nav_Item $leftItem
     * @param A $a
     * @return void
     */
    public function setupItem($leftItem, ElementComponent $a)
    {
        if ($leftItem->isActive()) {
            $a->addClass("active");
            $a->setAttribute("aria-current", "page");
        }
        if ($leftItem->isDisabled()) {
            $a->addClass("disabled");
        } else {
            $link = $leftItem->getLink();
            $a->setHref($link);
        }
        $a->setContent($leftItem->getText());
    }

    protected function afterBuildNode()
    {

        $this->addClass("navbar-expand-" . $this->expandBreakpoint);

        if($this->themeDark)
            $this->addClass("navbar-dark", "bg-dark");
        else
            $this->addClass("bg-light");

        {

            $fluid = new Div();
            $fluid->addClass("container-fluid");

            {

                $brand = new A();
                $brand->addClass("navbar-brand");

                if($this->brandLink != null){
                    $brand->setHref($this->brandLink);
                }

                if($this->brandIcon !== null){
                    if($this->brandIcon instanceof Component){
                        $brand->append($this->brandIcon);
                    } else if($this->brandIcon instanceof Path){
                        $img = new Img();
                        $img->setSource($this->brandIcon);
                        $img->style()->height(CSSSize::px(35));
                        $brand->append($img);
                    }
                }

                if($this->brandTitle !== null){
                    if(is_string($this->brandTitle)){
                        $component = Span::of($this->brandTitle);
                        $component->push(BSVerticalAlign::middle());
                    }
                    else
                        $component = $this->brandTitle;

                    if($this->brandIcon !== null)
                        $component->push(BSStyle::marginLeft(2));

                    $brand->append($component);
                }

                $fluid->append($brand);

            }

            $hasItems = !empty($this->leftItems) || $this->rightComponent !== null;

            if($hasItems){

                /*
                 <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                */

                $id = Active_NewId::go_newId();

                $button = new BSButton();
                $button->addClass("navbar-toggler");
                $button->setAttribute("data-bs-toggle", "collapse");
                $button->setAttribute("data-bs-target", "#" . $id);
                $button->setAttribute("aria-controls", "#" . $id);
                {

                    $span = new Span();
                    $span->addClass("navbar-toggler-icon");

                    $button->append($span);

                }
                $fluid->append($button);

                //<div class="collapse navbar-collapse" id="navbarText">
                $divCollapse = new Div();
                $divCollapse->addClass("collapse navbar-collapse");
                $divCollapse->setId($id);
                {
                    if(!empty($this->leftItems)){

                        //<ul class="navbar-nav me-auto mb-5 mb-lg-0">
                        $ul = new Ul();
                        $ul->addClass("navbar-nav");
                        //$ul->marginLeft("auto");
                        //$ul->marginBottom(5);
                        //$ul->marginBottom(5, $this->expandBreakpoint);
                        {
                            foreach ($this->leftItems as $leftItem) {

                                //<li class="nav-item">
                                $li = new Li();
                                $li->addClass("nav-item");

                                if($leftItem instanceof Nav_Item_Dropdown)
                                    $li->addClass("dropdown");

                                //<a class="nav-link active" aria-current="page" href="#">Home</a>
                                $a = new A();
                                $a->addClass("nav-link");

                                if($leftItem instanceof Nav_Item_Dropdown){
                                    $a->addClass("dropdown-toggle");
                                    $a->setAttribute("data-bs-toggle", "dropdown");
                                }

                                $this->setupItem($leftItem, $a);

                                $li->append($a);

                                if($leftItem instanceof Nav_Item_Dropdown) {

                                    $ul2 = new Ul();
                                    $ul2->addClass("dropdown-menu");

                                    foreach ($leftItem->getItems() as $dropdownItem){

                                        $li2 = new Li();

                                        if($dropdownItem instanceof Nav_Item){
                                            $a2 = new A();
                                            $a2->addClass("dropdown-item");

                                            $this->setupItem($dropdownItem, $a2);

                                        }else{
                                            $a2 = new Hr();
                                            $a2->addClass("dropdown-divider");
                                        }

                                        $li2->append($a2);

                                        $ul2->append($li2);

                                    }

                                    $li->append($ul2);

                                }

                                $ul->append($li);
                            }

                        }
                        $divCollapse->append($ul);

                    }

                    if($this->rightComponent !== null){

                        if(is_string($this->rightComponent)){
                            //<span class="navbar-text">
                            $span = new Span();
                            $span->setContent($this->rightComponent);
                            $divCollapse->append($span);

                        }else{
                            $this->rightComponent->push(BSStyle::marginLeft("auto"));
                            $divCollapse->append($this->rightComponent);
                        }

                    }

                }
                $fluid->append($divCollapse);

            }

            $this->append($fluid);

        }

        parent::afterBuildNode();
    }

    /**
     * @param callable $builder
     * @return NavBar
     */
    public static function add($builder = null)
    {
        $fc = new NavBar($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}