<?php

namespace monolitum\frontend\form;

use monolitum\core\Find;
use monolitum\entity\attr\Attr;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;

abstract class Form_Attr extends ElementComponent
{

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Attr|string
     */
    protected $attr;

    /**
     * @var AttrExt_Form
     */
    protected $formExt;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var bool
     */
    private $userSetInvalid = false;

    /**
     * @var string|ElementComponent
     */
    protected $invalidText;

    /**
     * @param HtmlElement $element
     * @param Attr|string $attrid
     * @param callable|null $builder
     */
    public function __construct($element, $attrid, $builder = null)
    {
        parent::__construct($element, $builder);
        $this->attr = $attrid;
    }

    protected function buildNode()
    {
        $this->form = Find::sync(Form::class);
        if(!($this->attr instanceof Attr))
            $this->attr = $this->form->getAttr($this->attr);
        $this->form->_registerFormAttr($this, $this->attr);

        $this->formExt = $this->attr->findExtension(AttrExt_Form::class);

        parent::buildNode();
    }

    /**
     * @param string|ElementComponent $string
     * @return $this
     */
    public function setInvalid($string=null)
    {
        $this->userSetInvalid = true;
        $this->invalidText = $string;
        return $this;
    }

    /**
     * @param string $label
     */
    public function label($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    protected function getName()
    {
        return $this->form->getAttrName($this->attr);
    }

    /**
     * @return string
     */
    protected function getLabel()
    {
        $label = null;
        if($this->formExt != null)
            $label = $this->formExt->getLabel();
        if($label == null)
            $label = $this->label;
        return $label;
    }

    /**
     * @return bool|null
     */
    protected function isValid()
    {
        $isValid = $this->form->isValid($this->attr);
        if($isValid === null)
            return null;
        return $isValid && !$this->userSetInvalid;
    }

    /**
     * @return bool
     */
    public function hasValue()
    {
        return $this->form->hasValue($this->attr);
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        return $this->form->getValidatedValue($this->attr)->getValue();
    }

    /**
     * @return Attr
     */
    public function getAttr()
    {
        return $this->attr;
    }

    /**
     * @return AttrExt_Form
     */
    public function getFormExt()
    {
        return $this->formExt;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

}