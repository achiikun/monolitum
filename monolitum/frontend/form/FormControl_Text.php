<?php
namespace monolitum\frontend\form;

use monolitum\core\GlobalContext;
use monolitum\frontend\html\HtmlElement;

class FormControl_Text extends FormControl
{

    /**
     * @param callable|null $builder
     */
    public function __construct(callable $builder = null)
    {
        parent::__construct(new HtmlElement("input"), $builder);
        $this->getElement()->setAttribute("type", "text");
    }

    /**
     * @param callable $builder
     * @return FormControl_Text
     */
    public static function add($builder)
    {
        $fc = new FormControl_Text($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}

