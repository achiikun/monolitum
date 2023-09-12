<?php

namespace monolitum\frontend\component;

use monolitum\core\GlobalContext;
use monolitum\core\Node;
use monolitum\core\panic\DevPanic;
use monolitum\core\Renderable;
use monolitum\core\Renderable_Node;
use monolitum\frontend\Component;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\html\HtmlElementContent;

class Reference extends Component {

    /**
     * @param callable|null $builder
     */
    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * References build its childs on parent, but renders in order
     * @param $child
     * @return Node
     */
    public function buildChild($child){
        return $this->getParent()->buildChild($child);
    }

    /**
     * @param Renderable_Node|Renderable $active
     * @param int|null $idx
     * @return $this
     */
    private function _append($active, $idx=null)
    {
        if ($active instanceof Renderable_Node) {
            if ($active->isBuilt() && $active->getParent() !== $this->getParent())
                throw new DevPanic("Component has a parent.");
            if ($this->isBuilding())
                $this->insertIntoArray($this->buildChild($active), $idx);
            else
                $this->insertIntoArray($active, $idx);
        }else {
            $this->insertIntoArray($active, $idx);
        }
        return $this;
    }
    /**
     * @param Renderable_Node|Renderable|HtmlElement|HtmlElementContent|string $active
     * @param int|null $idx
     * @return $this
     */
    public function append($active, $idx=null)
    {
        if ($active instanceof Renderable_Node) {
            $this->_append($active);
        }else if(is_string($active)){
            $this->_append(new HtmlElementContent($active), $idx);
        }else{
            $this->_append($active, $idx);
        }
        return $this;
    }

    /**
     * @param callable|null $builder
     * @return Component
     */
    public static function addEmpty($builder = null)
    {
        $c = new Reference($builder);
        GlobalContext::add($c);
        return $c;
    }

    public static function ofEmpty($builder = null)
    {
        return new Reference($builder);
    }

}