<?php

namespace monolitum\frontend\form;

use monolitum\backend\params\Manager_Params;
use monolitum\core\Find;
use monolitum\core\panic\DevPanic;
use monolitum\entity\attr\Attr;
use monolitum\entity\AttrExt_Validate;
use monolitum\entity\Entities_Manager;
use monolitum\entity\Entity;
use monolitum\entity\Model;
use monolitum\entity\ValidatedValue;

abstract class Form_Validator
{

    /**
     * @var array<string, ValidatedValue>
     */
    protected $build_validatedValues = [];

    /**
     * @var array<string, ValidatedValue>
     */
    protected $overwritten_validatedValues = [];

    /**
     * @var bool
     */
    protected $build_allValid = false;

    /**
     * @var bool
     */
    protected $build_isAlreadyValidated = false;

    /**
     * @var bool
     */
    protected $validate_attrs_all = true;

    /**
     * @var array<string>
     */
    protected $validate_attrs = [];

    /**
     * @var Form
     */
    protected $form;

    /**
     * @param string ...$attrs
     * @return void
     */
    public function validate_all_except(...$attrs){
        $this->validate_attrs_all = true;
        $this->validate_attrs = $attrs;

//        for($i = 0; $i < count($this->validate_attrs); $i++){
//            if(is_string($this->validate_attrs[$i]))
//                $this->validate_attrs[$i] = $this->getAttr($this->validate_attrs[$i]);
//        }

    }

    /**
     * @param string ...$attrs
     * @return void
     */
    public function validate_only(...$attrs){
        $this->validate_attrs_all = false;
        $this->validate_attrs = $attrs;

//        for($i = 0; $i < count($this->validate_attrs); $i++){
//            if(is_string($this->validate_attrs[$i]))
//                $this->validate_attrs[$i] = $this->getAttr($this->validate_attrs[$i]);
//        }

    }

    /**
     * @param Form $form
     * @return void
     */
    function _setForm($form){
        if($this->form !== null)
            throw new DevPanic("Validator can only be used in one Form");
        $this->form = $form;

    }

    function _validateAll(){
        $this->build_allValid = true;
        $this->build_isAlreadyValidated = true;
    }

    /**
     * @return bool
     */
    public function isAllValid()
    {
        if(!$this->build_isAlreadyValidated)
            $this->_validateAll();
        return $this->build_allValid;
    }

    /**
     * Read the value from the external source, validate it and return it.
     * @param Attr|string $attr
     * @return ValidatedValue
     */
    abstract function getValidatedValue($attr);

    /**
     * Ignore any validation and return the default value.
     * It will fetch the current editing entity or, if not, if the dev set any default value to the ext
     * @param Attr|string $attr
     * @return ValidatedValue
     */
    abstract function getDefaultValue($attr);

    /**
     * @param string $prefix prefix of the attribute that must be set
     * @return ValidatedValue if found, executed action string
     */
    abstract function validateSubmissionKey($prefix);

    /**
     * @param Entity $entity
     * @return void
     */
    public function writeValidValuesOn($entity)
    {
        throw new DevPanic("Writing values to an entity not supported in this validator");
    }

    /**
     * @param string|Model $model
     * @param string|Attr $attr
     * @return ValidatedValue
     */
    public static function go_findValidatedValue($model, $attr)
    {
        /** @var Manager_Params $varManager */
        $varManager = Find::sync(Manager_Params::class);

        $model = Entities_Manager::go_getModel($model);
        $attr = $model->getAttr($attr);

        $validated = $varManager->validate($model, $attr);

        /** @var AttrExt_Validate $ext */
        $ext = $attr->findExtension(AttrExt_Validate::class);
        if(!$ext)
            return $validated;

        return $ext->validate($validated);

    }

    /**
     * @param string|Attr $attrId
     * @return string|Attr
     */
    public function getAttr($attrId)
    {
        return $attrId;
    }

    /**
     * @param string|Attr $attrId
     * @return bool
     */
    public function isAttrInValidateList($attrId)
    {
        $attr = $this->getAttr($attrId);
        $inArray = in_array($attr->getId(), $this->validate_attrs);
        return $this->validate_attrs_all ^ $inArray;
    }

    /**
     * @param string|Attr $attrId
     * @param ValidatedValue $value
     */
    public function overwriteValidatedValue($attrId, $value)
    {
        $attr = $this->getAttr($attrId);
        $this->overwritten_validatedValues[$attr->getId()] = $value;
    }

}
