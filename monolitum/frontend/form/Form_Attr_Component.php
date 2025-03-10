<?php

namespace monolitum\frontend\form;

use monolitum\core\Find;
use monolitum\core\panic\DevPanic;
use monolitum\core\ts\TS;
use monolitum\entity\attr\Attr;
use monolitum\entity\AttrExt_Validate;
use monolitum\frontend\Component;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\ElementComponent_Ext;
use monolitum\frontend\html\HtmlElement;

abstract class Form_Attr_Component extends Component implements I_Form_Attr
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
     * @var string|TS
     */
    private $placeholder;

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
     * @var string|TS|ElementComponent}
     */
    protected $invalidText;

    /**
     * @var ElementComponent_Ext[]
     */
    protected $catchedExtensions = [];

    /**
     * @param HtmlElement $element
     * @param Attr|string $attrid
     * @param callable|null $builder
     */
    public function __construct($attrid, $builder = null)
    {
        parent::__construct($builder);
        $this->attr = $attrid;
    }

    function receiveActive($active)
    {
        if($active instanceof ElementComponent_Ext){
            $this->catchedExtensions[] = $active;
            return true;
        }
        return parent::receiveActive($active);
    }

    /**
     * @return ElementComponent_Ext[]
     */
    public function getCatchedExtensions()
    {
        return $this->catchedExtensions;
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
     * @param string|TS|ElementComponent $string
     * @return $this
     */
    public function setInvalid($string=null)
    {
        $this->userSetInvalid = true;
        $this->invalidText = $string;
        return $this;
    }

    /**
     * @param string|TS $label
     */
    public function label($label)
    {
        $this->label = $label;
    }

    /**
     * @param string|TS $placeholder
     */
    public function placeholder($placeholder)
    {
        $this->placeholder = $placeholder;
    }

    /**
     * @return string
     */
    protected function getFullFieldName()
    {
        return $this->form->_getFullFieldName($this->attr);
    }

    /**
     * @return string|TS
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
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
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
        return $this->form->getDisplayValue($this->attr)->isWellFormat();
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        return $this->form->getDisplayValue($this->attr)->getValue();
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