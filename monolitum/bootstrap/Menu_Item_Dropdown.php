<?php

namespace monolitum\bootstrap;

use monolitum\backend\params\Path;
use monolitum\core\Find;
use monolitum\frontend\component\A;
use monolitum\frontend\component\CSSLink;
use monolitum\frontend\component\Div;
use monolitum\frontend\component\JSScript;
use monolitum\frontend\component\Li;
use monolitum\frontend\component\Ul;
use function Sodium\add;

class Menu_Item_Dropdown extends Menu_Item implements Menu_Item_Holder
{

    /**
     * @var array<Menu_Item|Menu_Separator|Menu_Item_Dropdown>
     */
    private $items = [];

    /**
     * @var A
     */
    private $a;
    /**
     * @var Menu_Item_Holder|null
     */
    private $menuItemHolder;

    /**
     * @param Menu_Item|Menu_Separator|Menu_Item_Dropdown $leftItem
     * @return $this
     */
    public function addItem($leftItem)
    {
        $this->items[] = $leftItem;
        return $this;
    }

    /**
     * @return array<Menu_Item|Menu_Separator|Menu_Item_Dropdown>
     */
    public function getItems()
    {
        return $this->items;
    }

    public function openToLeft()
    {
        if($this->menuItemHolder === null)
            $this->menuItemHolder = Find::syncFrom(Menu_Item_Holder::class, $this->getParent());

        return $this->menuItemHolder->openToLeft();
    }

    public function isSubmenu()
    {
        return true;
    }

    public function isNav()
    {
        return false;
    }

    protected function afterBuildNode()
    {
        if($this->menuItemHolder === null)
            $this->menuItemHolder = Find::syncFrom(Menu_Item_Holder::class, $this->getParent());

        if($this->menuItemHolder->isNav()){
            $this->addClass("nav-item");
            $this->addClass("dropdown");
        }else{
            if($this->isSubmenu()){
                if($this->menuItemHolder->openToLeft()){
                    $this->addClass("dropend");
                }else{
                    $this->addClass("dropstart");
                }
            }
        }

        // Test submenu

        $this->a = A::add(function (A $it) {

            if($this->menuItemHolder->isNav()){
                $it->addClass("nav-link");
                $it->setAttribute("data-bs-auto-close", "outside");
            }else{
                //$this->assureSubmenuCodeAdded();
                $it->addClass("dropdown-item");
            }

            if($this->active){
                $it->addClass("active");
            }
            if($this->disabled){
                $it->addClass("disabled");
            }else{
                // Make dropdown menu
                $it->addClass("dropdown-toggle");
                $it->setAttribute("tabindex", "0");
                $it->setAttribute("data-bs-toggle", "dropdown");

//                if(!$isNav){
//                    $it->setAttribute("data-submenu", "");
//                }

            }
            $it->setContent($this->text);

        });

        if(!$this->disabled){
           Ul::add(function (Ul $it){
                $it->addClass("dropdown-menu");

                foreach ($this->items as $item){
                    $it->append($item);
                }

            });

        }

    }

}