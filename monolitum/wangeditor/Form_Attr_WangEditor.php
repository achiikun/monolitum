<?php

namespace monolitum\wangeditor;

use monolitum\core\GlobalContext;
use monolitum\core\Renderable;
use monolitum\frontend\Component;
use monolitum\frontend\form\Form_Attr_Component;
use monolitum\frontend\form\Form_Attr_ElementComponent;
use monolitum\frontend\form\FormControl_Hidden;
use monolitum\frontend\form\Interface_Form_Attr;
use monolitum\frontend\Rendered;

class Form_Attr_WangEditor extends Form_Attr_Component implements Interface_Form_Attr
{

    private $component;

    public function __construct($attrid, $builder = null)
    {
        parent::__construct($attrid, $builder);
    }

    public function afterBuildForm()
    {
        // TODO if hidden handle it different
        if($this->hidden){
            $this->component = new FormControl_Hidden(function (FormControl_Hidden $it){
                $it->setId($this->getFullFieldName());
                $it->setName($this->getFullFieldName());
                if($this->hasValue())
                    $it->setValue($this->getValue());
            });
        }else{
            $this->component = new WangEditor(function (WangEditor $it) {
                $it->setId($this->getFullFieldName());
                $it->setName($this->getFullFieldName());
                if($this->hasValue())
                    $it->setValue($this->getValue());

                if($this->disabled !== null ? $this->disabled : $this->getForm()->isDisabled())
                    $it->setDisabled(true);

            });
        }

    }

    public function render()
    {
        return Rendered::of($this->component);
    }

    /**
     * @param string $attrId
     * @param callable|null $builder
     * @return Form_Attr_WangEditor
     */
    public static function add($attrId, $builder = null)
    {
        $fc = new Form_Attr_WangEditor($attrId, $builder);
        GlobalContext::add($fc);
        return $fc;
    }

}