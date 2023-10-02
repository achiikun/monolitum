<?php

namespace monolitum\bootstrap;

use monolitum\frontend\component\A;
use monolitum\frontend\component\Div;
use function Sodium\add;

class Menu_Item_Dropdown extends Menu_Item
{

    /**
     * @var array<Menu_Item|Menu_Separator|Menu_Item_Dropdown>
     */
    private $items = [];


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

    protected function afterBuildNode()
    {

        if($this->getParent() instanceof Nav || $this->getParent() instanceof NavBar){
            $this->addClass("nav-item");
        }

        $this->addClass("dropdown");

        // Test submenu

        $this->a = A::add(function (A $it){
            // TODO this can be wrong if there is portals or references
            if($this->getParent() instanceof Nav || $this->getParent() instanceof NavBar) {

                $it->addClass("nav-link");
            }else{
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
                $it->setAttribute("data-toggle", "dropdown");

            }
            $it->setContent($this->text);

        });

        if(!$this->disabled){
            Div::add(function (Div $it){
                $it->addClass("dropdown-menu");

                foreach ($this->items as $item){
                    $it->append($item);
                }

            });
        }

    }

}