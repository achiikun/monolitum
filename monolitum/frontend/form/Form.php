<?php

namespace monolitum\frontend\form;

use monolitum\backend\params\Link;
use monolitum\backend\params\Manager_Params;
use monolitum\backend\params\Validator;
use monolitum\backend\res\Active_Create_HrefResolver;
use monolitum\backend\res\HrefResolver;
use monolitum\core\Find;
use monolitum\core\GlobalContext;
use monolitum\core\panic\DevPanic;
use monolitum\entity\attr\Attr;
use monolitum\entity\Entities_Manager;
use monolitum\entity\Entity;
use monolitum\entity\Model;
use monolitum\entity\ValidatedValue;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;

class Form extends ElementComponent
{

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var string
     */
    private $formId;

    /**
     * @var Entity
     */
    private $currentEntity;

    /**
     * @var Model|class-string
     */
    private $entityModel;

    /**
     * @var bool
     */
    private $validate_attrs_all = true;

    /**
     * @var array<string|Attr>
     */
    private $validate_attrs = [];

    /**
     * @var callable
     */
    private $onValidated = null;

    /**
     * Prevents the form to be validated. If this flag is enabled, the form is not validated. (The coming fields are kept, dough)
     * @var bool
     */
    private $notValidate = null;

    /**
     * Every form field is disabled if this flag is enabled
     * @var bool
     */
    private $disabled = false;

    /**
     * If this flag is enabled, form fields are not reporting error or validation.
     * @var bool
     */
    private $silentValidation;

    /**
     * The POST had a correct formid value.
     * So execute there are values available and the validation can be executed.
     * @var bool
     */
    private $build_isValidating = false;

    /**
     * @var array<Attr, ValidatedValue>
     */
    private $build_validatedValues = [];
    /**
     * @var bool
     */
    private $build_allValid = false;

    /**
     * @var array<string, mixed>
     */
    private $defaultValues = [];

    /**
     * @var HtmlElement
     */
    private $field_id;

    /**
     * @var bool
     */
    private $methodGET = false;

    /**
     * @var Link
     */
    private $link;

    /**
     * @var HrefResolver
     */
    private $linkResolver;

    /**
     * @var array<Attr>
     */
    private $formAttrs = [];

    /**
     * @param class-string|Model $entityClass
     * @param callable|null $builder
     */
    public function __construct($entityClass, $element=null, $builder = null)
    {
        parent::__construct($element != null ? $element : new HtmlElement("form"), $builder);
        $this->setAttribute("enctype", "multipart/form-data");
        $this->entityModel = $entityClass;
    }

    /**
     * @param string $attrString
     * @param mixed $value
     * @return $this
     */
    public function setDefaultValue($attrString, $value)
    {
        $this->defaultValues[$attrString] = $value;
        return $this;
    }

    /**
     * @param Link $link
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    public function setMethodGET()
    {
        $this->methodGET = true;
    }

    /**
     * this id will be used to retrieve form values back
     * @param string $formId
     */
    public function setFormId($formId)
    {
        $this->formId = $formId;
    }

    /**
     * @param Entity $currentEntity
     */
    public function setCurrentEntity($currentEntity)
    {
        $this->currentEntity = $currentEntity;
    }

    /**
     * @param array<string|Attr> ...$attrs
     * @return void
     */
    public function validate_all_except(...$attrs){
        $this->validate_attrs_all = true;
        $this->validate_attrs = $attrs;

        for($i = 0; $i < count($this->validate_attrs); $i++){
            if(is_string($this->validate_attrs[$i]))
                $this->validate_attrs[$i] = $this->getAttr($this->validate_attrs[$i]);
        }

    }

    /**
     * @param array<string|Attr> ...$attrs
     * @return void
     */
    public function validate_only(...$attrs){
        $this->validate_attrs_all = false;
        $this->validate_attrs = $attrs;

        for($i = 0; $i < count($this->validate_attrs); $i++){
            if(is_string($this->validate_attrs[$i]))
                $this->validate_attrs[$i] = $this->getAttr($this->validate_attrs[$i]);
        }

    }

    /**
     * Validator is the class that provides ValidatedValue's for tuples Model-Attr.
     * By default, closest Manager_Params is used.
     * @param Validator $validator
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param callable $onValidated
     */
    public function setOnValidated($onValidated)
    {
        $this->onValidated = $onValidated;
    }

    public function validateSilenty($silentValidation=true)
    {
        $this->silentValidation = $silentValidation;
    }

    /**
     * @return bool
     */
    public function isSilentValidation()
    {
        return $this->silentValidation;
    }

    public function notValidate($notValidate=true)
    {
        $this->notValidate = $notValidate;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }

    /**
     * @return string
     */
    public function getFormId()
    {
        return $this->formId;
    }

    /**
     * @param string $attrId
     * @return Attr
     */
    public function getAttr($attrId)
    {
        return $this->entityModel->getAttr($attrId);
    }

    /**
     * Retrieves the name of the form control (the one on the POST return)
     * @param Attr $attr
     * @return string
     */
    public function getAttrName($attr)
    {
        return $attr->getId();
    }

    /**
     * @return bool
     */
    public function isValidating()
    {
        return $this->build_isValidating;
    }

    /**
     * @return bool
     */
    public function isAllValid()
    {
        return $this->build_allValid;
    }

    /**
     * @param Attr $attr
     * @return bool|null
     */
    public function isValid($attr)
    {
        if($this->build_isValidating && !$this->silentValidation){
            if($this->entityModel->hasAttr($attr)){
                $validatedValue = $this->createValidatedValue($attr);
                return $validatedValue->isValid();
            }
        }
        return null;
    }

    /**
     * @param Attr $attr
     * @return bool
     */
    public function hasValue($attr)
    {
        if($this->build_isValidating){
            if($this->entityModel->hasAttr($attr)){
                $validatedValue = $this->createValidatedValue($attr);
                //$validatedValue = $this->build_validatedValues[$attr->getId()];
                return !$validatedValue->isNull();
            }
        }else if($this->currentEntity != null){
            if($this->entityModel->hasAttr($attr)){
                return $this->currentEntity->hasValue($attr->getId());
            }
        }else{
            return key_exists($attr->getId(), $this->defaultValues);
        }

        return false;
    }

    /**
     * Called from form fields telling that the attribute it handles is present.
     * @param Form_Attr $formAttr
     * @param Attr $attr
     * @return void
     */
    function _registerFormAttr($formAttr, $attr){
        $this->formAttrs[] = $attr;
    }

    private function createValidatedValue(Attr $attr)
    {
        if(key_exists($attr->getId(), $this->build_validatedValues)){
            return $this->build_validatedValues[$attr->getId()];
        }

        $ext = $attr->findExtension(AttrExt_Form::class);
        if(!$ext)
            return new ValidatedValue(true, null);

        $validatedValue = $this->validator->validate($this->entityModel, $attr);

        if(!$validatedValue->isValid() && $this->currentEntity != null){
            $validatedValue = new ValidatedValue(true, $this->currentEntity->getValue($attr));
        }

        $this->build_validatedValues[$attr->getId()] = $validatedValue;

        return $validatedValue;
    }

    public function writeValidValuesOn(Entity $entity)
    {

        foreach($this->entityModel->getAttrs() as $attr){
            $inArray = in_array($attr, $this->validate_attrs);
            if(!($this->validate_attrs_all ^ $inArray))
                continue;
            $validatedValue = $this->build_validatedValues[$attr->getId()];
            if($validatedValue->isValid())
                $entity->setValue($attr, $validatedValue->getValue());
        }

    }

    protected function buildNode()
    {
        if($this->entityModel !== null && !($this->entityModel instanceof Model)){
            // Throws if not found
            /** @var Entities_Manager $entities_Manager */
            $entities_Manager = Find::sync(Entities_Manager::class);
            $this->entityModel = $entities_Manager->getModel($this->entityModel);
        }

        if($this->entityModel !== null && $this->formId == null)
            $this->formId = $this->entityModel->getId();

        if($this->validator == null){
            /** @var Manager_Params $manager */
            $this->validator = Find::sync(Manager_Params::class);
            $this->validator = new Form_Validator($this->validator);
        }

        $validatedValue = $this->validator->validateStringPost("\$formid");

        $this->build_isValidating = false;
        if ($validatedValue->isValid() && $this->formId != null) {

            $value = $validatedValue->getValue();
            if ($value === $this->formId) {
                $this->build_isValidating = true;
            }

        }


        // Let build continue (attrs will locate this Form to get results)
        parent::buildNode();

        if($this->notValidate)
            $this->build_isValidating = false;

        if($this->link !== null){
            $active = new Active_Create_HrefResolver($this->link);
            $active->setParamsAlone();
            GlobalContext::add($active);
            $this->linkResolver = $active->getHrefResolver();
        }

        if(!$this->methodGET){

            $this->field_id = new HtmlElement("input");
            $this->field_id->setAttribute("type", "hidden");
            $this->field_id->setAttribute("name", "\$formid");

            if($this->formId == null)
                throw new DevPanic("Form must define an id", $this);

            $this->field_id->setAttribute("value", $this->formId, false);

            $this->push($this->field_id);

        }

        if($this->build_isValidating){

            $this->build_allValid = true;

            if($this->entityModel !== null){
                foreach($this->entityModel->getAttrs() as $attr){

                    if(key_exists($attr->getId(), $this->build_validatedValues)){
                        $validatedValue = $this->build_validatedValues[$attr->getId()];
                    }else{

                        $inArray = in_array($attr, $this->validate_attrs);
                        if(!($this->validate_attrs_all ^ $inArray))
                            continue;
                        $ext = $attr->findExtension(AttrExt_Form::class);
                        if(!$ext)
                            continue;
                        $validatedValue = $this->validator->validate($this->entityModel, $attr);

                    }

                    if(!$validatedValue->isValid()){
                        $this->build_allValid = false;
                    }
                    $this->build_validatedValues[$attr->getId()] = $validatedValue;
                }
            }

        }

        // Execute validation callback
        if($this->build_isValidating && $this->onValidated != null){

            $callback = $this->onValidated;
            $callback();
        }

    }

    protected function executeComponent()
    {

        $form = $this->getElement();
        if($this->methodGET)
            $form->setAttribute("method", "get");
        else
            $form->setAttribute("method", "post");

        if($this->linkResolver !== null){

            $form->setAttribute("action", $this->linkResolver->resolve());

            foreach($this->linkResolver->getParamsAlone() as $key => $value){
                $exists = false;
                foreach ($this->formAttrs as $attr) {
                    if(is_string($attr) && $key === $attr || $attr->getId() === $key){
                        $exists = true;
                        break;
                    }
                }
                if(!$exists){
                    $elem = new HtmlElement("input");
                    $elem->setAttribute("type", "hidden");
                    $elem->setAttribute("name", $key);
                    $elem->setAttribute("value", $value);
                    $this->push($elem, 0);
                }
            }
        }

        parent::executeComponent(); // TODO: Change the autogenerated stub
    }

    /**
     * @param Attr|string $attr
     * @return ValidatedValue
     */
    public function getValidatedValue($attr) {
        if($this->build_isValidating){
            if($attr instanceof Attr)
                return $this->createValidatedValue($attr);
//                return $this->build_validatedValues[$attr->getId()];
            else
                return $this->createValidatedValue($this->getAttr($attr));
//                return $this->build_validatedValues[$attr];
        }else if($this->currentEntity !== null){
            return new ValidatedValue(true, $this->currentEntity->getValue($attr));
        }else if(key_exists($attr->getId(), $this->defaultValues)){
            return new ValidatedValue(true, $this->defaultValues[$attr->getId()]);
        }
        return new ValidatedValue(false);
    }

    /**
     * @param class-string|Model $entity
     * @param HtmlElement $a
     * @param callable $builder
     * @return Form
     */
    public static function ofElement($entity, $a, $builder=null)
    {
        $fc = new Form(null, $a, $builder);

    }

    /**
     * @param class-string|Model $entity
     * @param callable $builder
     * @return Form
     */
    public static function add($entity, $builder)
    {
        $fc = new Form($entity, null, $builder);
        GlobalContext::add($fc);
        return $fc;
    }

}