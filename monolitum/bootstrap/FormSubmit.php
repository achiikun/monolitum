<?php
namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;
use monolitum\frontend\html\HtmlElement;

class FormSubmit extends BSElementComponent
{
    
    public function __construct(callable $builder)
    {
        parent::__construct(new HtmlElement("button"), $builder);
        $this->getElement()->setAttribute("type", "submit");
    }

    protected function buildNode()
    {
        parent::buildNode();
        $this->addClass("btn");
        $this->addClass("btn-primary");
    }

    /**
     * @param callable $builder
     * @return FormSubmit
     */
    public static function add($builder)
    {
        $fc = new FormSubmit($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}

