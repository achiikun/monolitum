<?php
namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;
use monolitum\frontend\html\HtmlElement;

class FormControl_Select_Option extends BSElementComponent
{

    /**
     * @param callable|null $builder
     */
    public function __construct(callable $builder = null, $value=null, $content=null)
    {
        parent::__construct(new HtmlElement("option"), $builder);
        $option = $this->getElement();
        $option->setAttribute("value", $value);
        $option->setContent($content);
    }

    /**
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $option = $this->getElement();
        $option->setAttribute("value", $value);
        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setSelected($value = true)
    {
        $option = $this->getElement();
        if ($value) {
            $option->setAttribute('selected', 'selected');
        } else {
            $option->setAttribute('selected', null);
        }
        return $this;
    }

    /**
     * @param callable $builder
     * @return FormControl_Select_Option
     */
    public static function add($builder, $value=null, $content=null)
    {
        $fc = new FormControl_Select_Option($builder, $value, $content);
        GlobalContext::add($fc);
        return $fc;
    }

}

