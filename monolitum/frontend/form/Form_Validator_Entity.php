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
     * @param $model class-string|AnonymousModel|Model
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

            // Jump attributes without Form specification
            $ext = $attr->findExtension(AttrExt_Form::class);
            if($ext === null)
                continue;

            $validatedValue = $this->getValidatedValue($attr);

            if($validatedValue !== null && !$validatedValue->isValid()){
                $this->build_allValid = false;
            }

        }

    }

    /**
     * @param Attr|string $attr
     * @return ValidatedValue
     */
    function getValidatedValue($attr)
    {
        if(is_string($this->model))
            $this->model = Entities_Manager::go_getModel($this->model);

        if(!($attr instanceof Attr))
            $attr = $this->model->getAttr($attr);

        if(key_exists($attr->getId(), $this->build_validatedValues)){
            $validatedValue = $this->build_validatedValues[$attr->getId()];
        }else{

            $inArray = in_array($attr->getId(), $this->validate_attrs);
            if(!($this->validate_attrs_all ^ $inArray)){
                $validatedValue = null;
            } else {
                $validatedValue = $this->validator->validate($this->model, $attr, $this->form->_getValidatePrefix());
            }

            $this->build_validatedValues[$attr->getId()] = $validatedValue;

        }

        return $validatedValue;
    }

    /**
     * @param Attr|string $attr
     * @return ValidatedValue
     */
    public function getDefaultValue($attr)
    {
        if($this->currentEntity === null)
            return new ValidatedValue(false);

        return new ValidatedValue(true, true, $this->currentEntity->getValue($attr));

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