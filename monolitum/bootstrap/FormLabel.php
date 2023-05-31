<?php
namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;
use monolitum\frontend\html\HtmlElement;

class FormLabel extends BSElementComponent
{

    /**
     * @var string
     */
    private $class;

    public function __construct(callable $builder = null, $class = "form-label")
    {
        parent::__construct(new HtmlElement("label"), $builder);
        $this->class = $class;
    }

    public function setName($name)
    {
        $label = $this->getElement();
        $label->setAttribute("for", $name);
    }

    protected function buildComponent()
    {
        $this->addClass($this->class);
    }

    /**
     * @param callable $builder
     * @return FormLabel
     */
    public static function add($builder)
    {
        $fc = new FormLabel($builder);
        GlobalContext::add($fc);
        return $fc;
    }

    /**
     * @param callable $builder
     * @return FormLabel
     */
    public static function addString($content, $builder = null)
    {
        $fc = new FormLabel($builder);
        $fc->setContent($content);
        GlobalContext::add($fc);
        return $fc;
    }

}

