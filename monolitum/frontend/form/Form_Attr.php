<?php

namespace monolitum\frontend\form;

use monolitum\core\Find;
use monolitum\entity\attr\Attr;
use monolitum\frontend\Component;

abstract class Form_Attr extends Component
{

    /**
     * @var Form
     */
    private $form;

    /**
     * @var Attr|string
     */
    private $attr;

    /**
     * @var AttrExt_Form
     */
    protected $formExt;

    /**
     * @var string
     */
    protected $label;

    /**
     * @param Attr|string $attrid
     * @param callable|null $builder
     */
    public function __construct($attrid, $builder = null)
    {
        parent::__construct($builder);
        $this->attr = $attrid;
    }

    protected function buildNode()
    {
        $this->form = Find::sync(Form::class);
        if(!($this->attr instanceof Attr))
            $this->attr = $this->form->getAttr($this->attr);
        $this->form->_registerFormAttr($this->attr->getId());

        $this->formExt = $this->attr->findExtension(AttrExt_Form::class);

        parent::buildNode();
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
        return $this->form->isValid($this->attr);
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