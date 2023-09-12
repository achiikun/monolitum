<?php

namespace monolitum\bootstrap;

use monolitum\bootstrap\style\BSColSpanResponsive;
use monolitum\core\GlobalContext;
use monolitum\entity\attr\Attr;
use monolitum\entity\attr\Attr_Bool;
use monolitum\entity\attr\Attr_Date;
use monolitum\entity\attr\Attr_Decimal;
use monolitum\entity\attr\Attr_File;
use monolitum\entity\attr\Attr_Int;
use monolitum\entity\attr\Attr_String;
use monolitum\frontend\Component;
use monolitum\frontend\component\Div;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\form\AttrExt_Form;
use monolitum\frontend\form\AttrExt_Form_Int;
use monolitum\frontend\form\AttrExt_Form_String;
use monolitum\frontend\form\Form;
use monolitum\frontend\html\HtmlElement;

class Form_Attr extends \monolitum\frontend\form\Form_Attr
{

    /**
     * @var Component
     */
    private $formWrapper;

    /**
     * @var string|ElementComponent
     */
    private $formText = null;

    /**
     * @var bool
     */
    protected $disabled = null;

    /**
     * @var bool|null
     */
    private $labelRendersAfterControl = null;

    /**
     * @var BSColSpanResponsive
     */
    private $isRow;

    /**
     * @param string $attrid
     * @param callable|null $builder
     */
    public function __construct($attrid, $builder = null)
    {
        parent::__construct(new HtmlElement("div"), $attrid, $builder);
        $this->formWrapper = $this;
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
     * @param ElementComponent|string|null $formText
     * @return $this
     */
    public function setFormText($formText)
    {
        $this->formText = $formText;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasValue()
    {
        return $this->form->hasValue($this->attr);
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

    /**
     * @param BSColSpanResponsive $isRow
     */
    public function setIsRow($isRow)
    {
        $this->isRow = $isRow;
        return $this;
    }

    protected function afterBuildNode()
    {

        $attr = $this->getAttr();
//        $ext = $this->getFormExt();

        $invalidFeedback = null;
        if($this->isValid() === false && $this->invalidText){
            $invalidFeedback = new Div(function (Div $it){
                $it->addClass("invalid-feedback");
                $it->push($this->invalidText);
            });
        }

        $formText = null;
        if($this->formText !== null){
            if($this->formText instanceof ElementComponent){
                $formText = $this->formText;
                $formText->addClass("form-text");
            }else{
                $formText = new Div(function (Div $it){
                    $it->addClass("form-text");
                    $it->push($this->formText);
                });
            }

        }

        if($attr instanceof Attr_Bool){

            $this->formWrapper->addClass("form-check");

            $this->formWrapper->push(
                new FormControl_CheckBox(function(FormControl_CheckBox $it){
                    $it->setId($this->getName());
                    $it->setName($this->getName());
                    if($this->hasValue())
                        $it->setValue($this->getValue());

                    if($this->disabled !== null ? $this->disabled : $this->form->isDisabled())
                        $it->setDisabled(true);

                })
            );

            $this->formWrapper->push(
                new FormLabel(function(FormLabel $it){
                    $it->setName($this->getName());
                    $it->setContent($this->getLabel());
                }, "form-check-label")
            );

            if($invalidFeedback){
                $this->formWrapper->push($invalidFeedback);
            }

            if($formText){
                $this->formWrapper->push($formText);
            }

            $this->labelRendersAfterControl = true;

        }else{

            $this->formWrapper->addClass("form-group");

            $formLabel = new FormLabel(function(FormLabel $it){
                $it->setName($this->getName());
                $it->setContent($this->getLabel());
            }, $this->isRow != null ? "col-form-label" : "form-label");

            if($this->isRow){
                $this->formWrapper->addClass("row");
            }

            $formControl = $this->createFormControl();

            if($this->isRow != null){
                $this->isRow->buildInto($formLabel, true);

                $formControlWrapper = new Div();

                $formControlWrapper->push($formControl);
                $this->isRow->buildInto($formControlWrapper);

                if($this->labelRendersAfterControl){
                    $this->formWrapper->push($formControlWrapper);
                    $this->formWrapper->push($formLabel);
                }else{
                    $this->formWrapper->push($formLabel);
                    $this->formWrapper->push($formControlWrapper);
                }

            }else{

                if($this->labelRendersAfterControl){
                    $this->formWrapper->push($formControl);
                    $this->formWrapper->push($formLabel);
                }else{
                    $this->formWrapper->push($formLabel);
                    $this->formWrapper->push($formControl);
                }

            }

            if($invalidFeedback){
                $this->formWrapper->push($invalidFeedback);
            }

            if($formText){
                $this->formWrapper->push($formText);
            }

        }

        parent::afterBuildNode(); // TODO: Change the autogenerated stub
    }

    protected function executeNode()
    {

        //$this->buildChild($this->formWrapper);

        parent::executeNode();
    }


    protected function createFormControl()
    {

        $attr = $this->getAttr();
        $ext = $this->getFormExt();
        $isValid = $this->isValid();

        $formControl = null;

        if($attr instanceof Attr_String){

            if($ext instanceof AttrExt_Form_String && $ext->hasEnum()){

                $formControl = new FormControl_Select(function (FormControl_Select $it) use ($ext) {
                    $it->setId($this->getName());
                    $it->setName($this->getName());

                    $selected = null;
                    if($this->hasValue())
                        $selected = $this->getValue();

                    if($this->disabled !== null ? $this->disabled : $this->form->isDisabled())
                        $it->setDisabled(true);

                    if($ext->isNullable()){

                        $nullLabel = $ext->getNullLabel();

                        FormControl_Select_Option::add(function (FormControl_Select_Option $it) use ($selected, $nullLabel) {



                            if($nullLabel !== null){
                                $it->setContent($nullLabel);
                            }else{
                                $it->setContent("");
                            }

                            $it->setValue("");

                            if($selected === null)
                                $it->setSelected();

                        });

                    }

                    foreach ($ext->getEnums() as $itemKey => $itemValue) {

                        FormControl_Select_Option::add(function (FormControl_Select_Option $it) use ($selected, $itemKey, $itemValue) {

                            $item = null;
                            if(is_string($itemKey)){
                                $item = $itemKey;
                                if(is_array($itemValue)){
                                    $it->setContent($itemValue[0]);
                                }else{
                                    $it->setContent($itemValue);
                                }
                            }else if(is_array($itemValue)){
                                $it->setContent($itemValue[1]);
                                $item = $itemValue[0];
                            }else{
                                $it->setContent($itemValue);
                            }

                            $it->setValue($item);

                            if($item == $selected)
                                $it->setSelected();

                        });

                    }

                });

            }else if($ext instanceof AttrExt_Form_String && $ext->isHtml()){

//                $formControl = new EditorJS(function (EditorJS $it) use ($ext) {
//                    $it->setId($this->getName());
//                    $it->setName($this->getName());
//
//                    if($this->hasValue())
//                        $it->setValue($this->getValue());
//
//                    $it->style()->height(CSSSize::px(150));
//
//                });

                $formControl = new FormControl_TextArea_Html(function (FormControl_TextArea_Html $it) use ($ext) {
                    $it->setId($this->getName());
                    $it->setName($this->getName());

                    if($this->hasValue())
                        $it->setValue($this->getValue());

                });

            }else if($ext instanceof AttrExt_Form_String && $ext->isPassword()){

                $formControl = new FormControl_Password(function(FormControl_Password $it) use ($isValid) {
                    $it->setId($this->getName());
                    $it->setName($this->getName());
                    if($this->hasValue())
                        $it->setValue($this->getValue());
                    if($isValid !== null)
                        $it->addClass($isValid ? "is-valid" : "is-invalid");

                    if($this->disabled !== null ? $this->disabled : $this->form->isDisabled())
                        $it->setDisabled(true);

                    //todo ask form for default value
                });

            }else{

                $formControl = new FormControl_Text(function(FormControl_Text $it) use ($isValid) {
                    $it->setId($this->getName());
                    $it->setName($this->getName());
                    if($this->hasValue())
                        $it->setValue($this->getValue());
                    if($isValid !== null)
                        $it->addClass($isValid ? "is-valid" : "is-invalid");

                    if($this->disabled !== null ? $this->disabled : $this->form->isDisabled())
                        $it->setDisabled(true);

                });

            }

        }else if($attr instanceof Attr_Int){

            $formControl = new FormControl_Number(function(FormControl_Number $it) use ($ext, $isValid) {
                $it->setId($this->getName());
                $it->setName($this->getName());
                if($this->hasValue()){
                    $it->setValue($this->getValue());
                }

                if($ext instanceof AttrExt_Form_Int){
                    $it->min($ext->getMin());
                    $it->max($ext->getMax());
                }

                if($isValid !== null)
                    $it->addClass($isValid ? "is-valid" : "is-invalid");

                if($this->disabled !== null ? $this->disabled : $this->form->isDisabled())
                    $it->setDisabled(true);

            });

        }else if($attr instanceof Attr_Decimal){

            $formControl = new FormControl_Number(function(FormControl_Number $it) use ($attr, $isValid) {
                $it->setId($this->getName());
                $it->setName($this->getName());
                $decimals = $attr->getDecimals();

                $it->step(1 / pow(10, $decimals));

                if($this->hasValue()){
                    $it->setValue($this->getValue() / pow(10, $decimals));
                }
                if($isValid !== null)
                    $it->addClass($isValid ? "is-valid" : "is-invalid");

                if($this->disabled !== null ? $this->disabled : $this->form->isDisabled())
                    $it->setDisabled(true);

            });

        }else if($attr instanceof Attr_Date){

            $formControl = new FormControl_Date(function(FormControl_Date $it) use ($isValid) {
                $it->setId($this->getName());
                $it->setName($this->getName());
                if($this->hasValue()){
                    $datetime = $this->getValue();
                    $it->setValue(date_format($datetime, "Y-m-d"));
                }
                if($isValid !== null)
                    $it->addClass($isValid ? "is-valid" : "is-invalid");

                if($this->disabled !== null ? $this->disabled : $this->form->isDisabled())
                    $it->setDisabled(true);

            });

        }else if($attr instanceof Attr_File){

            $formControl = new FormControl_File(function(FormControl_File $it) use ($ext, $isValid) {
                $it->setId($this->getName());
                $it->setName($this->getName());

                if($isValid !== null)
                    $it->addClass($isValid ? "is-valid" : "is-invalid");

                if($this->disabled !== null ? $this->disabled : $this->form->isDisabled())
                    $it->setDisabled(true);

            });

        }

        return $formControl;

    }

    /**
     * @param string $attrid
     * @param callable|null $builder
     * @return Form_Attr
     */
    public static function add($attrid, $builder = null)
    {
        $fc = new Form_Attr($attrid, $builder);
        GlobalContext::add($fc);
        return $fc;
    }


}