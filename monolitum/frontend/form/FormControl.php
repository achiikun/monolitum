<?php
namespace monolitum\frontend\form;

use monolitum\frontend\ElementComponent;

class FormControl extends ElementComponent
{

    /**
     * @var string
     */
    private $class;

    public function __construct($element, callable $builder = null, $class = "form-control")
    {
        parent::__construct($element, $builder);
        $this->class = $class;
    }

    /**
     * @param $hint true: "on", false: "off", string: "hint"
     * @return void
     */
    public function autocomplete($hint=true)
    {
        $element = $this->getElement();
        $element->setAttribute("autocomplete", $hint === true ? "on" : ($hint === false ? "off" : $hint));
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName($name){
        $element = $this->getElement();
        $element->setAttribute("name", $name);
    }

    /**
     * @param string $value
     * @return void
     */
    public function setValue($value){
        $element = $this->getElement();

        if($element->getTag() === "textarea"){
            $element->setContent($value);
        }else{
            $element->setAttribute("value", $value);
        }
    }

    /**
     * @param bool $value
     * @return void
     */
    public function setDisabled($value = true)
    {
        $element = $this->getElement();
        $element->setAttribute("disabled", $value ? "disabled" : null);
    }

    public function convertToHidden()
    {
        $element = $this->getElement();
        $element->setTag("input");
        $element->setAttribute("type", "hidden");
    }

    protected function buildNode()
    {
        parent::buildNode();
        $this->addClass($this->class);
    }

}

