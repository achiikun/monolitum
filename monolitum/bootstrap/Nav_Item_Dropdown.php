<?php

namespace monolitum\bootstrap;

use monolitum\backend\params\Link;
use monolitum\backend\params\Path;
use monolitum\backend\res\HrefResolver;

class Nav_Item_Dropdown extends Nav_Item
{

    /**
     * @var array<Nav_Item|Nav_Separator>
     */
    private $items = [];


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
     * @return Nav_Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

}