<?php

namespace monolitum\quilleditor;

use monolitum\bootstrap\FormLabel;
use monolitum\core\GlobalContext;
use monolitum\frontend\component\Div;
use monolitum\frontend\ElementComponent_Ext;
use monolitum\frontend\form\Form_Attr_Component;
use monolitum\frontend\form\FormControl_Hidden;
use monolitum\wangeditor\Form_Attr_WangEditor;

class Form_Attr_QuillEditor extends Form_Attr_Component
{

    private $component;

    public function __construct($attrid, $builder = null)
    {
        parent::__construct($attrid, $builder);
        $this->experimental_letBuildChildsAfterBuild = true;
    }

    public function getValue()
    {

        $quillValue = parent::getValue();

        if($quillValue instanceof QuillDocument)
            $quillValue = $quillValue->makeDelta();

        return $quillValue;
    }

    public function afterBuildForm()
    {

        if($this->hidden){
            $this->component = new FormControl_Hidden(function (FormControl_Hidden $it){
                $it->setId($this->getFullFieldName());
                $it->setName($this->getFullFieldName());
                if($this->hasValue())
                    $it->setValue($this->getValue());
            });
        }else{

            $this->component = new Div(function (Div $it){
                $it->addClass("form-group");

                $it->push(...$this->getCatchedExtensions());

                $it->append(new FormLabel(function(FormLabel $it){
                    $it->setName($this->getFullFieldName());
                    $it->setContent($this->getLabel());
                }, "form-label"));

                $it->append(new QuillEditor(function (QuillEditor $it) {
                    $it->setId($this->getFullFieldName());
                    $it->setName($this->getFullFieldName());
                    if($this->hasValue())
                        $it->setValue($this->getValue());

                    if($this->getPlaceholder() != null)
                        $it->setPlaceholder($this->getPlaceholder());

                    if($this->disabled !== null ? $this->disabled : $this->getForm()->isDisabled())
                        $it->setDisabled();

                }));

            });

        }

        $this->append($this->component);

    }

    /**
     * @param string $attrId
     * @param callable|null $builder
     * @return Form_Attr_QuillEditor
     */
    public static function add($attrId, $builder = null)
    {
        $fc = new Form_Attr_QuillEditor($attrId, $builder);
        GlobalContext::add($fc);
        return $fc;
    }

}