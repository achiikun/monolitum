<?php

namespace monolitum\bootstrap;

use monolitum\bootstrap\style\BSColSpanResponsive;
use monolitum\core\GlobalContext;
use monolitum\core\ts\TS;
use monolitum\core\ts\TSLang;
use monolitum\entity\attr\Attr_Bool;
use monolitum\entity\attr\Attr_Date;
use monolitum\entity\attr\Attr_Decimal;
use monolitum\entity\attr\Attr_File;
use monolitum\entity\attr\Attr_Int;
use monolitum\entity\attr\Attr_String;
use monolitum\entity\AttrExt_Validate_Int;
use monolitum\entity\AttrExt_Validate_String;
use monolitum\frontend\Component;
use monolitum\frontend\component\Div;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\form\AttrExt_Form_String;
use monolitum\frontend\form\Form_Attr_ElementComponent;
use monolitum\frontend\form\FormControl_CheckBox;
use monolitum\frontend\form\FormControl_Date;
use monolitum\frontend\form\FormControl_File;
use monolitum\frontend\form\FormControl_Number;
use monolitum\frontend\form\FormControl_Password;
use monolitum\frontend\form\FormControl_Select;
use monolitum\frontend\form\FormControl_Select_Option;
use monolitum\frontend\form\FormControl_Text;
use monolitum\frontend\html\HtmlElement;

class BS_Form_Attr extends Form_Attr_ElementComponent
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
        $this->experimental_letBuildChildsAfterBuild = true;
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
     * @param BSColSpanResponsive $isRow
     */
    public function setIsRow($isRow)
    {
        $this->isRow = $isRow;
        return $this;
    }

    public function afterBuildForm()
    {
        $attr = $this->getAttr();
//        $ext = $this->getFormExt();

        if($this->hidden === true){
            $this->formWrapper->append($this->createFormControl());
        }else{

            $invalidFeedback = null;
            if($this->isValid() === false){
                $invalidText = TS::unwrap($this->getInvalidText(), TSLang::findWithOverwritten($this->overwrittenLanguage));
                if($invalidText !== null){
                    $invalidFeedback = new Div(function (Div $it) use ($invalidText) {
                        $it->addClass("invalid-feedback");
                        $it->append($invalidText);
                    });
                }
            }

            $formText = null;
            if($this->formText !== null){
                if($this->formText instanceof ElementComponent){
                    $formText = $this->formText;
                    $formText->addClass("form-text");
                }else{
                    $formText = new Div(function (Div $it){
                        $it->addClass("form-text");
                        $it->append($this->formText);
                    });
                }

            }

            if($attr instanceof Attr_Bool){

                $this->formWrapper->addClass("form-check");

                $this->formWrapper->append($this->createFormControl());

                $this->formWrapper->append(
                    new FormLabel(function(FormLabel $it){
                        $it->setName($this->getFullFieldName());
                        $it->setContent($this->getLabel());
                    }, "form-check-label")
                );

                if($invalidFeedback){
                    $this->formWrapper->append($invalidFeedback);
                }

                if($formText){
                    $this->formWrapper->append($formText);
                }

                $this->labelRendersAfterControl = true;

            }else{

                $this->formWrapper->addClass("form-group");

                $formLabel = new FormLabel(function(FormLabel $it){
                    $it->setName($this->getFullFieldName());
                    $it->setContent($this->getLabel());
                }, $this->isRow != null ? "col-form-label" : "form-label");

                if($this->isRow){
                    $this->formWrapper->addClass("row");
                }

                $formControl = $this->createFormControl();

                if($this->isRow != null){
                    $this->isRow->buildInto($formLabel, true);

                    $formControlWrapper = new Div();

                    $formControlWrapper->append($formControl);
                    $this->isRow->buildInto($formControlWrapper);

                    if($this->labelRendersAfterControl){
                        $this->formWrapper->append($formControlWrapper);
                        $this->formWrapper->append($formLabel);
                    }else{
                        $this->formWrapper->append($formLabel);
                        $this->formWrapper->append($formControlWrapper);
                    }

                }else{

                    if($this->labelRendersAfterControl){
                        $this->formWrapper->append($formControl);
                        $this->formWrapper->append($formLabel);
                    }else{
                        $this->formWrapper->append($formLabel);
                        $this->formWrapper->append($formControl);
                    }

                }

                if($invalidFeedback){
                    $this->formWrapper->append($invalidFeedback);
                }

                if($formText){
                    $this->formWrapper->append($formText);
                }

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
        $formExt = $this->getFormExt();
        $validateExt = $this->getValidateExt();
        $isValid = $this->isValid();

        $formControl = null;

        $finalLanguage = TSLang::findWithOverwritten($this->overwrittenLanguage); // TODO Active get finalLanguage

        if($attr instanceof Attr_Bool){

            $formControl = new FormControl_CheckBox(function(FormControl_CheckBox $it){
                $it->setId($this->getFullFieldName());
                $it->setName($this->getFullFieldName());
                if($this->hasValue())
                    $it->setValue($this->getValue());

                if($this->hidden === true)
                    $it->convertToHidden();

                if($this->disabled !== null ? $this->disabled : $this->form->isDisabled())
                    $it->setDisabled(true);

            });

        } if($attr instanceof Attr_String){

            if($validateExt instanceof AttrExt_Validate_String && $validateExt->hasEnum()){

                $formControl = new FormControl_Select(function (FormControl_Select $it) use ($isValid, $finalLanguage, $formExt, $validateExt) {
                    $it->setId($this->getFullFieldName());
                    $it->setName($this->getFullFieldName());

                    $selected = null;
                    if($this->hasValue())
                        $selected = $this->getValue();
                    $it->setValue($selected);

                    if($this->disabled !== null ? $this->disabled : $this->form->isDisabled())
                        $it->setDisabled();

                    if($isValid !== null)
                        $it->addClass($isValid ? "is-valid" : "is-invalid");

                    if($this->hidden === true)
                        $it->convertToHidden();

                    $it->setPicker();

                    $nullLabel = null;
                    if($formExt instanceof AttrExt_Form_String){
                        $nullLabel = $formExt->getNullLabel();

                        $it->setSearchable($formExt->isSearchable());
                    }

                    if($validateExt->isNullable()){

                        FormControl_Select_Option::add(function (FormControl_Select_Option $it) use ($finalLanguage, $selected, $nullLabel) {

                            if($nullLabel !== null){
                                $it->setContent(TS::unwrap($nullLabel, $finalLanguage));
                            }else{
                                $it->setContent("");
                            }

                            $it->setValue("");

                            if($selected === null)
                                $it->setSelected();

                        });

                    }else{

                        $it->setAttribute("data-placeholder", TS::unwrap($nullLabel, $finalLanguage));

                        FormControl_Select_Option::add(function (FormControl_Select_Option $it) use ($selected) {
                            $it->setContent("");
                        });

                    }

                    foreach ($validateExt->getEnums() as $itemKey => $itemValue) {

                        FormControl_Select_Option::add(function (FormControl_Select_Option $it) use ($finalLanguage, $validateExt, $selected, $itemKey, $itemValue) {

                            $item = null;
                            if(is_string($itemKey)){
                                $item = $itemKey;
                            }else if(is_array($itemValue)){
                                $item = $itemValue[0];
                            }

                            $it->setValue($item);
                            $it->setContent(TS::unwrap($validateExt->getEnumString($item), $finalLanguage));

                            if($item == $selected)
                                $it->setSelected();

                        });

                    }

                });

            }else if($formExt instanceof AttrExt_Form_String && $formExt->isHtml()){

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

                $formControl = new FormControl_TextArea_Html(function (FormControl_TextArea_Html $it) use ($formExt) {
                    $it->setId($this->getFullFieldName());
                    $it->setName($this->getFullFieldName());
                    $it->autocomplete(false);

                    if($this->hidden === true)
                        $it->convertToHidden();

                    if($this->hasValue())
                        $it->setValue($this->getValue());

                });

            }else if($formExt instanceof AttrExt_Form_String && $formExt->isPassword()){

                $formControl = new FormControl_Password(function(FormControl_Password $it) use ($isValid) {
                    $it->setId($this->getFullFieldName());
                    $it->setName($this->getFullFieldName());
                    $it->autocomplete(false);
                    if($this->hasValue())
                        $it->setValue($this->getValue());
                    if($isValid !== null)
                        $it->addClass($isValid ? "is-valid" : "is-invalid");

                    if($this->hidden === true)
                        $it->convertToHidden();

                    if($this->disabled !== null ? $this->disabled : $this->form->isDisabled())
                        $it->setDisabled(true);

                    //todo ask form for default value
                });

            }else{

                $formControl = new FormControl_Text(function(FormControl_Text $it) use ($formExt, $finalLanguage, $isValid) {
                    $it->setId($this->getFullFieldName());
                    $it->setName($this->getFullFieldName());
                    $it->autocomplete(false);

                    if($formExt instanceof AttrExt_Form_String){
                        $inputType = $formExt->getInputType();
                        if($inputType !== null)
                            $it->setInputType($inputType);
                    }

                    if($this->hasValue())
                        $it->setValue($this->getValue());
                    if($isValid !== null)
                        $it->addClass($isValid ? "is-valid" : "is-invalid");

                    if($this->getPlaceholder() != null)
                        $it->setPlaceholder(TS::unwrap($this->getPlaceholder(), $finalLanguage));

                    if($this->hidden === true)
                        $it->convertToHidden();

                    if($this->disabled !== null ? $this->disabled : $this->form->isDisabled())
                        $it->setDisabled(true);

                });

            }

        }else if($attr instanceof Attr_Int){

            $formControl = new FormControl_Number(function(FormControl_Number $it) use ($validateExt, $isValid) {
                $it->setId($this->getFullFieldName());
                $it->setName($this->getFullFieldName());
                if($this->hasValue()){
                    $it->setValue($this->getValue());
                }

                if($validateExt instanceof AttrExt_Validate_Int){
                    $it->min($validateExt->getMin());
                    $it->max($validateExt->getMax());
                }

                if($this->hidden === true)
                    $it->convertToHidden();

                if($isValid !== null)
                    $it->addClass($isValid ? "is-valid" : "is-invalid");

                if($this->disabled !== null ? $this->disabled : $this->form->isDisabled())
                    $it->setDisabled(true);

            });

        }else if($attr instanceof Attr_Decimal){

            $formControl = new FormControl_Number(function(FormControl_Number $it) use ($attr, $isValid) {
                $it->setId($this->getFullFieldName());
                $it->setName($this->getFullFieldName());
                $decimals = $attr->getDecimals();

                $it->step(1 / pow(10, $decimals));

                if($this->hidden === true)
                    $it->convertToHidden();

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
                $it->setId($this->getFullFieldName());
                $it->setName($this->getFullFieldName());
                if($this->hasValue()){
                    $datetime = $this->getValue();
                    if($datetime !== null)
                        $it->setValue(date_format($datetime, "Y-m-d"));
                }
                if($isValid !== null)
                    $it->addClass($isValid ? "is-valid" : "is-invalid");

                if($this->hidden === true)
                    $it->convertToHidden();

                if($this->disabled !== null ? $this->disabled : $this->form->isDisabled())
                    $it->setDisabled(true);

            });

        }else if($attr instanceof Attr_File){

            $formControl = new FormControl_File(function(FormControl_File $it) use ($isValid) {
                $it->setId($this->getFullFieldName());
                $it->setName($this->getFullFieldName());

                if($this->hidden === true)
                    $it->convertToHidden();

                if($isValid !== null)
                    $it->addClass($isValid ? "is-valid" : "is-invalid");

                if($this->disabled !== null ? $this->disabled : $this->form->isDisabled())
                    $it->setDisabled(true);

            });

        }

        return $formControl;

    }

    public function render()
    {
        if($this->hidden === true){
            return parent::renderChilds();
        }else{
            return parent::render();
        }
    }

    /**
     * @param string $attrId
     * @param callable|null $builder
     * @return BS_Form_Attr
     */
    public static function add($attrId, $builder = null)
    {
        $fc = new BS_Form_Attr($attrId, $builder);
        GlobalContext::add($fc);
        return $fc;
    }

    /**
     * @param string $attrId
     * @param callable|null $builder
     * @return BS_Form_Attr
     */
    public static function addHidden($attrId, $builder = null)
    {
        $fc = new BS_Form_Attr($attrId, $builder);
        $fc->hidden();
        GlobalContext::add($fc);
        return $fc;
    }


}