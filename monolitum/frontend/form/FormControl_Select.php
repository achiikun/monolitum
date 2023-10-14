<?php
namespace monolitum\frontend\form;

use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\Rendered;

class FormControl_Select extends FormControl
{

    /**
     * @param callable|null $builder
     */
    public function __construct(callable $builder = null)
    {
        parent::__construct(new HtmlElement("select"), $builder, "form-select");
    }

    public function render()
    {
        // No childs are rendered if it is hidden
        if($this->getElement()->getAttribute("type") !== "hidden"){
            Renderable_Node::renderRenderedTo($this->renderChilds(), $this->getElement());
        }
        return Rendered::of($this->getElement());
    }

    /**
     * @param callable $builder
     * @return FormControl_Select
     */
    public static function add($builder)
    {
        $fc = new FormControl_Select($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}

