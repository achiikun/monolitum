<?php

namespace monolitum\fontawesome;

use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;

class FA_Icon extends ElementComponent
{

    const SOLID = "solid";


    /**
     * @var string
     */
    private $collection = FA_Icon::SOLID;

    /**
     * @var string
     */
    private $icon;

    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement('i'), $builder);
    }

    /**
     * @param string $icon
     */
    public function setCollectionIcon($collection, $icon)
    {
        $this->collection = $collection;
        $this->icon = $icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    protected function afterBuildNode()
    {
        $this->addClass("fa-" . $this->collection, "fa-" . $this->icon);
        parent::afterBuildNode(); // TODO: Change the autogenerated stub
    }

    /**
     * @param string $icon
     * @return FA_Icon
     */
    public static function ofIcon($icon){
        $i = new FA_Icon();
        $i->setIcon($icon);
        return $i;
    }

    /**
     * @param string $collection
     * @param string $icon
     * @return FA_Icon
     */
    public static function ofCollectionIcon($collection, $icon){
        $i = new FA_Icon();
        $i->setCollectionIcon($collection, $icon);
        return $i;
    }


}