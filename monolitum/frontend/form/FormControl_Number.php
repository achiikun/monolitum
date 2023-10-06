<?php
namespace monolitum\frontend\form;

use monolitum\core\GlobalContext;
use monolitum\frontend\html\HtmlElement;

class FormControl_Number extends FormControl
{

    /**
     * @param callable|null $builder
     */
    public function __construct(callable $builder = null)
    {
        parent::__construct(new HtmlElement("input"), $builder);
        $this->getElement()->setAttribute("type", "number");
    }

    /**
     * @param int|null $value
     * @return $this
     */
    public function min($value){
        $element = $this->getElement();
        $element->setAttribute("min", $value);
        return $this;
    }

    /**
     * @param int|null $value
     * @return $this
     */
    public function max($value){
        $element = $this->getElement();
        $element->setAttribute("max", $value);
        return $this;
    }


    /**
     * @param mixed $value
     * @return $this
     */
    public function step($value){
        $element = $this->getElement();
        $element->setAttribute("step", $value);
        return $this;
    }

    /**
     * @param callable $builder
     * @return FormControl_Number
     */
    public static function add($builder)
    {
        $fc = new FormControl_Number($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}

