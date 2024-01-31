<?php

namespace monolitum\frontend\form;

use monolitum\backend\params\Validator;
use monolitum\entity\AnonymousModel;
use monolitum\entity\attr\Attr;
use monolitum\entity\Entities_Manager;
use monolitum\entity\Entity;
use monolitum\entity\Model;
use monolitum\entity\ValidatedValue;

class Form_Validator_Entity extends Form_Validator
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var class-string|AnonymousModel|Model
     */
    private $model;

    /**
     * @var Entity
     */
    private $currentEntity;

    /**
     * @param Validator $validator
     * @param class-string|AnonymousModel|Model $model
     */
    public function __construct($validator, $model)
    {
        $this->validator = $validator;
        $this->model = $model;
    }

    /**
     * @param Entity $currentEntity
     * @return $this
     */
    public function setCurrentEntity($currentEntity)
    {
        $this->currentEntity = $currentEntity;
        return $this;
    }

    public function _validateAll()
    {
        parent::_validateAll();

        if(is_string($this->model))
            $this->model = Entities_Manager::go_getModel($this->model);

        foreach($this->model->getAttrs() as $attr){

            // Skip attributes without Form specification
            $ext = $attr->findExtension(AttrExt_Form::class);
            if($ext === null)
                continue;

            $inArray = in_array($attr->getId(), $this->validate_attrs);
            if($this->validate_attrs_all ^ $inArray){

                $validatedValue = $this->getValidatedValue($attr);

                if($validatedValue !== null && !$validatedValue->isValid()){
                    $this->build_allValid = false;
                }

            }

        }

    }

    /**
     * @param Attr|string $attr
     * @return ValidatedValue
     */
    function getValidatedValue($attr)
    {
        // Retrieve model
        if(is_string($this->model))
            $this->model = Entities_Manager::go_getModel($this->model);

        // Retrieve attribute
        if(!($attr instanceof Attr))
            $attr = $this->model->getAttr($attr);

        if(key_exists($attr->getId(), $this->build_validatedValues)){
            // The attr has been already validated
            $validatedValue = $this->build_validatedValues[$attr->getId()];
        }else{

            // Validate the value that comes from outside
            $validatedValue = $this->validator->validate($this->model, $attr, $this->form->_getValidatePrefix());

            // If not valid, try to substitute with a valid value
            if(!$validatedValue->isValid()){

                // Skip attribute without Form specification
                /** @var AttrExt_Form $ext */
                $ext = $attr->findExtension(AttrExt_Form::class);
                if($ext !== null && $ext->isSubstituteNotValid()){
                    $validatedValue = new ValidatedValue(true, true, $ext->getDef());
                }

            }

            // These lines are commented, because the value is not valid
            // Set the current value in editing entity if not valid
//            if(!$validatedValue->isValid() && $this->currentEntity !== null)
//                $validatedValue = new ValidatedValue(true, true, $this->currentEntity->getValue($attr));

            // Store it if the value must be validated, if not, then the dev only wanted to check some foreign value.
            $inArray = in_array($attr->getId(), $this->validate_attrs);
            if($this->validate_attrs_all ^ $inArray){
                $this->build_validatedValues[$attr->getId()] = $validatedValue;
            }

        }

        return $validatedValue;
    }

    /**
     * @param Attr|string $attr
     * @return ValidatedValue
     */
    public function getDefaultValue($attr)
    {
        if($this->currentEntity !== null)
            return new ValidatedValue(true, true, $this->currentEntity->getValue($attr));

        // Retrieve model
        if(is_string($this->model))
            $this->model = Entities_Manager::go_getModel($this->model);

        // Retrieve attribute
        if(!($attr instanceof Attr))
            $attr = $this->model->getAttr($attr);

        // Skip attribute without Form specification
        /** @var AttrExt_Form $ext */
        $ext = $attr->findExtension(AttrExt_Form::class);
        if($ext !== null && $ext->isDefaultSet()){
            return new ValidatedValue(true, true, $ext->getDef());
        }

        return new ValidatedValue(false);

    }

    /**
     * @param $prefix
     * @return ValidatedValue|null
     */
    public function validateSubmissionAction($prefix)
    {
        return $this->validator->validateStringPost_NameStartingWith_ReturnEnding($prefix);
    }

    /**
     * @param Entity $entity
     * @return void
     */
    public function writeValidValuesOn($entity)
    {
        if(is_string($this->model))
            $this->model = Entities_Manager::go_getModel($this->model);

        foreach($this->model->getAttrs() as $attr){
            $inArray = in_array($attr->getId(), $this->validate_attrs);
            if(!($this->validate_attrs_all ^ $inArray))
                continue;
            $validatedValue = $this->build_validatedValues[$attr->getId()];
            if($validatedValue !== null && $validatedValue->isValid())
                $entity->setValue($attr, $validatedValue->getValue());
        }

    }

    /**
     * @param $attrId
     * @return Attr
     */
    public function getAttr($attrId)
    {
        if(is_string($this->model))
            $this->model = Entities_Manager::go_getModel($this->model);

        return $this->model->getAttr($attrId);
    }

}