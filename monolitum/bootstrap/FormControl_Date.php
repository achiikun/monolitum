<?php
namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;
use monolitum\frontend\html\HtmlElement;

class FormControl_Date extends FormControl
{

    /**
     * @param callable|null $builder
     */
    public function __construct(callable $builder = null)
    {
        parent::__construct(new HtmlElement("input"), $builder);
        $this->getElement()->setAttribute("type", "date");
    }

    /**
     * @param callable $builder
     * @return FormControl_Date
     */
    public static function add($builder)
    {
        $fc = new FormControl_Date($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}

