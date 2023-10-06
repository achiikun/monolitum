<?php

namespace monolitum\frontend\form;

use monolitum\core\Find;
use monolitum\core\panic\DevPanic;
use monolitum\entity\attr\Attr;
use monolitum\entity\AttrExt_Validate;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;

abstract class Form_Attr_ElementComponent extends ElementComponent implements Interface_Form_Attr
{

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var Attr
     */
    protected $attr;

    /**
     * @var AttrExt_Form
     */
    protected $formExt;

    /**
     * @var AttrExt_Validate
     */
    protected $validateExt;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var bool
     */
    protected $disabled = null;

    /**
     * @var bool
     */
    protected $hidden = null;

    /**
     * @var bool
     */
    private $userSetInvalid = false;

    /**
     * @var string|ElementComponent
     */
    protected $invalidText;

    /**
     * @var bool
     */
    private $hasOverriddenValue = false;

    /**
     * @var mixed
     */
    private $overriddenValue;

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

    /**
     * @param bool $disabled
     * @return $this
     */
    public function disabled($disabled=true)
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * @param bool|null $hidden
     * @return $this
     */
    public function hidden($hidden=true)
    {
        $this->hidden = $hidden;
        return $this;
    }

    protected function buildNode()
    {
        $this->form = Find::sync(Form::class);
        $this->attr = $this->form->_getAttr($this->attr);

        if(!($this->attr instanceof Attr))
            throw new DevPanic("Form_Attr_ElementComponent works only with real Attr");

        $this->form->_registerFormAttr($this, $this->attr);
        $this->formExt = $this->attr->findExtension(AttrExt_Form::class);
        $this->validateExt = $this->attr->findExtension(AttrExt_Validate::class);

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
     * @param mixed $value
     * @return $this
     */
    public function setOverrideValue($value)
    {
        $this->hasOverriddenValue = true;
        $this->overriddenValue = $value;
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
    protected function getFullFieldName()
    {
        return $this->form->_getFullFieldName($this->attr);
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
     * Returns if the value that user set is invalid.
     * @return bool|null
     */
    protected function isValid()
    {
        if($this->form->isSilentValidation())
            return null;
        $isValid = $this->form->getValidatedValue($this->attr);
        if($isValid === null)
            return null;
        return $isValid->isValid() && !$this->userSetInvalid;
    }

    /**
     * Tells if there is a value ready to be displayed to the user.
     * @return bool
     */
    public function hasValue()
    {
        return $this->hasOverriddenValue ? true : $this->form->getDisplayValue($this->attr)->isWellFormat();
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        return $this->hasOverriddenValue ? $this->overriddenValue : $this->form->getDisplayValue($this->attr)->getValue();
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
     * @return AttrExt_Validate
     */
    public function getValidateExt()
    {
        return $this->validateExt;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

}