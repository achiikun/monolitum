<?php

namespace monolitum\frontend\form;

use monolitum\backend\globals\Active_NewId;
use monolitum\backend\params\AttrExt_Param;
use monolitum\backend\params\Link;
use monolitum\backend\params\Manager_Params;
use monolitum\backend\params\Validator;
use monolitum\backend\res\Active_Create_HrefResolver;
use monolitum\backend\res\HrefResolver;
use monolitum\core\Find;
use monolitum\core\GlobalContext;
use monolitum\core\Monolitum;
use monolitum\core\panic\DevPanic;
use monolitum\core\Renderable_Node;
use monolitum\entity\AnonymousModel;
use monolitum\entity\attr\Attr;
use monolitum\entity\Entities_Manager;
use monolitum\entity\Entity;
use monolitum\entity\Model;
use monolitum\entity\ValidatedValue;
use monolitum\frontend\Component;
use monolitum\frontend\component\A;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\Rendered;

class Form extends Component
{

    /**
     * @var Form_Validator
     */
    private $validator;

    /**
     * If flag is true, submission button has not form info, so it cannot be identified later.
     * @var bool
     */
    private $anonymousSubmission = false;

    /**
     * @var array<string, mixed>
     */
    private $defaultValues = [];

    /**
     * @var string
     */
    private $formId;

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
     * @var array<string, Form_Attr>
     */
    private $formAttrs = [];

    /**
     * @var array<Form_Submit>
     */
    private $formSubmit = [];

    ///
    /// HIDDEN VALUES
    ///

    /**
     * @var bool|string[]
     */
    private $copyParams = false;

    /**
     * @var string[]
     */
    private $removeParams = [];

    /**
     * @var array<string, string>
     */
    private $addParams = [];

    /**
     * TODO Compute
     * @var null
     */
    private $computedParamsAlone = null;

    ///
    /// INTERNAL FIELDS
    ///

    /**
     * @var Form|null
     */
    private $rootForm = null;

    /**
     * @var HtmlElement
     */
    private $formElement;

    /**
     * @var bool
     */
    private $hasNestedForms = false;

    /**
     * @var array<Form>
     */
    private $nestedForms = [];

    /**
     * The POST had a correct formid value.
     * So execute there are values available and the validation can be executed.
     * @var bool
     */
    private $build_isValidating = false;

    private $build_overrideSubmitLinks = false;

    /**
     * @var array<string, ValidatedValue>
     */
    protected $build_displayValidatedValues = [];

    /**
     * @param Form_Validator|null $validator
     * @param string $formId
     * @param callable|null $builder
     */
    public function __construct($validator, $formId, $builder = null)
    {
        parent::__construct($builder);
        $this->validator = $validator;
        $this->formId = $formId;
        if($this->validator !== null)
            $this->validator->_setForm($this);

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
     * @param array<string> ...$attrs
     * @return $this
     */
    public function validate_all_except(...$attrs){

        if($this->validator !== null){
            $this->validator->validate_all_except(...$attrs);
            return $this;
        }else{
            throw new DevPanic("Setting attributes to validate is not supported without validator");
        }

    }

    /**
     * @param array<string> ...$attrs
     * @return $this
     */
    public function validate_only(...$attrs){

        if($this->validator !== null){
            $this->validator->validate_only(...$attrs);
            return $this;
        }else{
            throw new DevPanic("Setting attributes to validate is not supported without validator");
        }

    }

    /**
     * @param $anonymousSubmission
     * @return $this
     */
    public function setAnonymousSubmission($anonymousSubmission=true)
    {
        $this->anonymousSubmission = $anonymousSubmission;
        return $this;
    }

    /**
     * @param callable $onValidated
     */
    public function setOnValidated($onValidated)
    {
        $this->onValidated = $onValidated;
    }

    public function validateSilently($silentValidation=true)
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
     * @param string|Attr $attrId
     * @return string|Attr
     */
    function _getAttr($attrId)
    {
        if($this->validator !== null)
            return $this->validator->getAttr($attrId);
        return $attrId;
    }

    /**
     * Retrieves the name of the form control (the one on the POST return)
     * @param string|Attr $attr
     * @return string
     */
    function _getAttrName($attr)
    {
        // Append the form Id if it is necessary to be appended

        if($attr instanceof Attr){

            /** @var AttrExt_Param|null $attrExt_Param */
            $attrExt_Param = $attr->findExtension(AttrExt_Param::class);

            if($attrExt_Param != null){
                $attrId = $attrExt_Param->getName();
            }else{
                $attrId = $attr->getId();
            }

        }else {
            $attrId = $attr;
        }

        if($this->hasNestedForms || $this->rootForm !== null)
            $attrId = $this->formId . "__" . $attrId;

        return $attrId;
    }

    /**
     * Returns the prefix for the attribute "name" of the input submit element.
     * It must contain the formid.
     * @return string
     */
    function _getSubmitPrefix(){
        if($this->anonymousSubmission)
            return null;
        return $this->formId . "_submit__";
    }

    /**
     * @return string|null
     */
    function _getSubmitMethod()
    {
        if($this->hasNestedForms || $this->rootForm !== null)
            return $this->methodGET ? "get" : "post";
        return null;
    }

    /**
     * @return string|null
     */
    function _getSubmitLink()
    {
        if(($this->rootForm !== null || $this->build_overrideSubmitLinks) && $this->linkResolver !== null)
            return $this->linkResolver->resolve();
        return null;
    }

    /**
     * @return string
     */
    public function _getValidatePrefix()
    {
        if($this->hasNestedForms || $this->rootForm !== null)
            return $this->formId . "__";
        return null;
    }

    /**
     * @return bool
     */
    public function isValidating()
    {
        return $this->build_isValidating;
    }


//    /**
//     * @param Attr|string $attr
//     * @return bool|null
//     */
//    public function isValid($attr)
//    {
//        if($this->build_isValidating){
//            if($this->validator === null)
//                return null;
//            $validatedValue = $this->createValidatedValue($attr);
//            return $validatedValue->isValid();
//        }
//        return null;
//    }
//
//    /**
//     * @param Attr $attr
//     * @return bool
//     */
//    public function hasValue($attr)
//    {
//        if($this->build_isValidating){
//            if($this->entityModel->hasAttr($attr)){
//                $validatedValue = $this->createValidatedValue($attr);
//                //$validatedValue = $this->build_validatedValues[$attr->getId()];
//                return !$validatedValue->isNull();
//            }
//        }else if($this->currentEntity != null){
//            if($this->entityModel->hasAttr($attr)){
//                return $this->currentEntity->hasValue($attr->getId());
//            }
//        }else{
//            return key_exists($attr->getId(), $this->defaultValues);
//        }
//
//        return false;
//    }


    /**
     * @param Attr|string $attr
     * @return ValidatedValue
     */
    public function getValidatedValue($attr)
    {

        if(!$this->build_isValidating)
            return null;

        if($this->validator === null){
            return new ValidatedValue(false);
        }else{
            return $this->validator->getValidatedValue($attr);
        }

    }

    /**
     * @param Attr|string $attr
     * @return ValidatedValue
     */
    public function getDisplayValue($attr) {

        if($this->validator === null){
            if(key_exists($attr->getId(), $this->defaultValues)){
                return new ValidatedValue(true, true, $this->defaultValues[$attr->getId()]);
            }else{
                return new ValidatedValue(false);
            }
        }else{

            $validatedValue = $this->validator->getValidatedValue($attr);

            if($validatedValue->isValid() || $validatedValue->isWellFormat())
                return $validatedValue;

            if(key_exists($attr->getId(), $this->defaultValues)){
                $validatedValue = new ValidatedValue(true, true, $this->defaultValues[$attr->getId()]);
            }else{
                $validatedValue = $this->validator->getDefaultValue($attr);
            }

            return $validatedValue;
        }

    }

    /**
     * @param Form $form
     * @param string $key
     * @param string $value
     * @return HtmlElement
     */
    public function createHiddenInput($form, $key, $value)
    {
        $exists = false;
        foreach ($form->formAttrs as $attr => $formAttr) {
            if ($key === $attr) {
                $exists = true;
                break;
            }
        }
        if (!$exists) {
            $elem = new HtmlElement("input");
            $elem->setAttribute("type", "hidden");
            if ($form->formId !== null) {
                $elem->setAttribute("name", $form->formId . "__" . $key);
            } else {
                $elem->setAttribute("name", $key);
            }
            $elem->setAttribute("value", $value);
            return $elem;
        }
        return null;
    }

    /**
     * Called from nested Forms, in order this root Form to manage all ids.
     * @param Form $form
     * @return void
     */
    function _registerNestedForm($form){
        assert($this->rootForm === null);
        $this->hasNestedForms = true;
        $this->nestedForms[] = $form;
    }

    /**
     * Called from form fields telling that the attribute it handles is present.
     * @param Form_Attr $formAttr
     * @param Attr $attr
     * @return void
     */
    function _registerFormAttr($formAttr, $attr){
        $this->formAttrs[$attr->getId()] = $formAttr;
    }

    /**
     * Called from form fields telling that the attribute it handles is present.
     * @param Form_Submit $formSubmit
     * @return void
     */
    function _registerFormSubmit($formSubmit){
        $this->formSubmit[] = $formSubmit;
    }


//    /**
//     * @param Attr|string $attr
//     * @return ValidatedValue
//     */
//    private function createValidatedValue($attr)
//    {
//        assert($this->validator !== null);
//
//        if($attr instanceof Attr){
//
//            if(key_exists($attr->getId(), $this->build_validatedValues)){
//                return $this->build_validatedValues[$attr->getId()];
//            }
//
//            $validatedValue = $this->validator->getValidatedValue($attr);
//
//            if(!$validatedValue->isValid() && $this->currentEntity != null){
//                $validatedValue = new ValidatedValue(true, true, $this->currentEntity->getValue($attr));
//            }
//
//            $this->build_validatedValues[$attr->getId()] = $validatedValue;
//
//            return $validatedValue;
//
//        }else{
//
//            if(key_exists($attr, $this->build_validatedValues)){
//                return $this->build_validatedValues[$attr];
//            }
//
//            $validatedValue = $this->validator->getValidatedValue($attr);
//
//            if(!$validatedValue->isValid() && $this->currentEntity != null){
//                $validatedValue = new ValidatedValue(true, true, $this->currentEntity->getValue($attr));
//            }
//
//            $this->build_validatedValues[$attr->getId()] = $validatedValue;
//
//            return $validatedValue;
//
//        }
//    }

    /**
     * @param Entity $entity
     * @return void
     */
    public function writeValidValuesOn($entity)
    {

        if($this->validator !== null){
            $this->validator->writeValidValuesOn($entity);
        }else{
            throw new DevPanic("Writing values to an entity is not supported without validator");
        }

    }

    /**
     * @return bool
     */
    public function isAllValid()
    {
        if($this->validator !== null){
            return $this->validator->isAllValid();
        }else{
            throw new DevPanic("Writing values to an entity is not supported without validator");
        }
    }

    protected function buildNode()
    {
        // Generate an ID to identify the submission of this form if not exist

        if($this->formId === null)
            $this->formId = Active_NewId::go_newId("form");

        // Find root form before all
        // If there are nested forms, they will find me and the real root form

        /** @var Form $parentForm */
        $parentForm = Find::syncFrom(Form::class, $this->getParent(), true, true);
        if($parentForm !== null){
            $this->rootForm = $parentForm->rootForm;
            if($this->rootForm == null)
                $this->rootForm = $parentForm;
            $this->rootForm->_registerNestedForm($this);
        }

        parent::buildNode(); // TODO: Change the autogenerated stub
    }

    protected function afterBuildNode()
    {

        if($this->validator !== null){

            $validatedValueAction = $this->validator->validateSubmissionAction($this->formId . "_submit__");

            if($validatedValueAction->isValid() && !$this->notValidate){
                $this->build_isValidating = true;

                $this->validator->_validateAll();

                $action = $validatedValueAction->getValue();
                if(is_string($action) && !empty($action)){

                    // Find submit button that triggered this action
                    foreach ($this->formSubmit as $submit){
                        $submitAction = $submit->getAction();
                        if($submitAction === $action){
                            // Found
                            $onValidated = $submit->getOnValidated();
                            if($onValidated !== null){
                                $onValidated($this, $action);
                            }

                        }
                    }

                    // Execute validation callback
                    if($this->onValidated != null){

                        $callback = $this->onValidated;
                        $callback($this, $action);

                    }

                }else{

                    // Execute validation callback
                    if($this->onValidated != null){

                        $callback = $this->onValidated;
                        $callback($this);

                    }

                }

            }


        }

        if($this->link !== null){
            $active = new Active_Create_HrefResolver($this->link);
//            $active->setParamsAlone();
            GlobalContext::add($active);
            $this->linkResolver = $active->getHrefResolver();
        }

        if($this->rootForm === null){
            // Create form
            $this->formElement = new HtmlElement("form");
            $this->formElement->setAttribute("enctype", "multipart/form-data");

            if(!$this->hasNestedForms){
                // All submit have the same method

                if($this->methodGET)
                    $this->formElement->setAttribute("method", "get");
                else
                    $this->formElement->setAttribute("method", "post");

            }else{

                if($this->linkResolver !== null){
                    // Very likely will be different
                    $this->build_overrideSubmitLinks = true;
                }else{
                    foreach ($this->nestedForms as $form) {
                        $otherLinkResolver = $form->linkResolver;
                        if ($otherLinkResolver !== null) {
                            $this->build_overrideSubmitLinks = true;
                            break;
                        }
                    }
                }

            }


            if($this->linkResolver !== null){

                $this->formElement->setAttribute("action", $this->linkResolver->resolve());

                if($this->computedParamsAlone !== null) {
                    foreach ($this->computedParamsAlone as $key => $value) {
                        $input = $this->createHiddenInput($this, $key, $value);
                        if ($input !== null)
                            $this->append($input, 0);
                    }
                }
            }

            foreach ($this->nestedForms as $form){
                $computedParamsAlone = $form->computedParamsAlone;
                if($computedParamsAlone !== null){
                    foreach($computedParamsAlone as $key => $value){
                        $input = $this->createHiddenInput($form, $key, $value);
                        if($input !== null)
                            $this->append($input, 0);
                    }

                }
            }


        }

        foreach ($this->formAttrs as $value){
            $value->afterBuildForm();
        }

        foreach ($this->formSubmit as $value){
            $value->afterBuildForm();
        }


    }

    protected function executeComponent()
    {

        parent::executeComponent(); // TODO: Change the autogenerated stub
    }

    public function render()
    {
        $parentRender = parent::render();
        if($this->formElement !== null){
            Renderable_Node::renderRenderedTo($parentRender, $this->formElement);
            return Rendered::of($this->formElement);
        }else{
            return $parentRender; // TODO: Change the autogenerated stub
        }
    }

    /**
     * Creates a Form using Manager_Params as provider and a Model as model.
     * @param class-string|Model $model
     * @param callable $builder
     * @return Form
     */
    public static function addFromModel($model, $builder)
    {
        /** @var Manager_Params $manager_params */
        $manager_params = Find::sync(Manager_Params::class);
        $fc = new Form(new Form_Validator_Entity(
            $manager_params,
            $model
        ), null, $builder);
        GlobalContext::add($fc);
        return $fc;
    }

    /**
     * Creates a Form using Manager_Params as provider and a Model as model.
     * @param class-string|Model $model
     * @param callable $builder
     * @return Form
     */
    public static function addFromModelAndEntity($model, $entity, $builder)
    {
        /** @var Manager_Params $manager_params */
        $manager_params = Find::sync(Manager_Params::class);
        $fc = new Form((new Form_Validator_Entity(
            $manager_params,
            $model
        ))->setCurrentEntity($entity), null, $builder);
        GlobalContext::add($fc);
        return $fc;
    }

    /**
     * Creates a Form using Manager_Params as provider and a Model as model.
     * @param class-string|Model $model
     * @param string $formId
     * @param callable $builder
     * @return Form
     */
    public static function addFromModelAndId($model, $formId, $builder)
    {
        /** @var Manager_Params $manager_params */
        $manager_params = Find::sync(Manager_Params::class);
        $fc = new Form(new Form_Validator_Entity(
            $manager_params,
            $model
        ), $formId, $builder);
        GlobalContext::add($fc);
        return $fc;
    }

    /**
     * Creates a Form without validator.
     * @param callable $builder
     * @return Form
     */
    public static function addAnonymous($builder)
    {
        $fc = new Form(null, null, $builder);
        $fc->setAnonymousSubmission();
        GlobalContext::add($fc);
        return $fc;
    }

    /**
     * Creates a Form without validator.
     * @param callable $builder
     * @return Form
     */
    public static function anonymous($builder)
    {
        $fc = new Form(null, null, $builder);
        $fc->setAnonymousSubmission();
        return $fc;
    }

}