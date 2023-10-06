<?php
namespace monolitum\frontend\form;

use monolitum\core\GlobalContext;
use monolitum\frontend\html\HtmlElement;

class FormControl_CheckBox extends FormControl
{

    /**
     * @param callable|null $builder
     */
    public function __construct(callable $builder = null)
    {
        parent::__construct(new HtmlElement("input"), $builder, "form-check-input");
        $this->getElement()->setAttribute("type", "checkbox");
    }

    public function setValue($value){
        $element = $this->getElement();
        $element->setAttribute($value ? "checked" : null, "");
    }
    
    /**
     * @param callable $builder
     * @return FormControl_CheckBox
     */
    public static function add($builder)
    {
        $fc = new FormControl_CheckBox($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}

