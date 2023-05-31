<?php
namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;
use monolitum\frontend\html\HtmlElement;

class FormControl_Select extends FormControl
{

    /**
     * @param callable|null $builder
     */
    public function __construct(callable $builder = null)
    {
        parent::__construct(new HtmlElement("select"), $builder, "form-select");
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

