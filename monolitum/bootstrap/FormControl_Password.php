<?php
namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;
use monolitum\frontend\html\HtmlElement;

class FormControl_Password extends FormControl
{
    /**
     * @param callable|null $builder
     */
    public function __construct(callable $builder = null)
    {
        parent::__construct(new HtmlElement("input"), $builder);
        $this->getElement()->setAttribute("type", "password");
    }

    /**
     * @param callable $builder
     * @return FormControl_Password
     */
    public static function add($builder = null)
    {
        $fc = new FormControl_Password($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}

