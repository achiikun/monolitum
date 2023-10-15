<?php

namespace monolitum\backend\params;

use monolitum\core\GlobalContext;
use monolitum\entity\attr\Attr;
use monolitum\entity\attr\Attr_Bool;
use monolitum\entity\attr\Attr_Date;
use monolitum\entity\attr\Attr_Decimal;
use monolitum\entity\attr\Attr_File;
use monolitum\entity\attr\Attr_Int;
use monolitum\entity\attr\Attr_String;
use monolitum\entity\AttrExt_Validate;
use monolitum\entity\Entities_Manager;
use monolitum\entity\Model;
use monolitum\entity\ValidatedValue;
use monolitum\core\Find;
use monolitum\backend\Manager;
use monolitum\core\panic\DevPanic;

class Manager_Params extends Manager implements Validator
{

    //** @var Model[] by name */
    //private $sessionModels = [];

    /** @var Model[] by name */
    private $getModels = [];

    /** @var Model[] by name */
    private $postModels = [];

    /**
     * @param callable|null $builder
     */
    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param string|Model $class
     */
    public function addModel_GET($class)
    {
        $model = Entities_Manager::go_getModel($class);
        $this->getModels[$model->getIdOrClass()] = $model;
    }

    /**
     * @param string|Model $class
     */
    public function addModel_POST($class)
    {
        $model = Entities_Manager::go_getModel($class);
        $this->postModels[$model->getIdOrClass()] = $model;
    }

    protected function receiveActive($active)
    {
        if($active instanceof Active_Get_Params){

            $returnArray = [];

            $categories = $active->getCategory();
            if(is_string($categories))
                $this->fill_params($returnArray, $categories, $active->getInclude(), $active->getExceptions());
            else if(is_array($categories)){
                foreach ($categories as $category){
                    $this->fill_params($returnArray, $category, $active->getInclude(), $active->getExceptions());
                }
            }

            $active->setCurrentParams($returnArray);

            return true;
        }else if($active instanceof Active_Param_Value){
            $validatedValue = $this->validate($active->getModel(), $active->getAttr());
            $active->setValidatedValue($validatedValue);
            return true;
        }

        return parent::receiveActive($active); // TODO: Change the autogenerated stub
    }

    /**
     * @param array<string, string> $returnArray
     * @param string $category
     * @param bool|string[] $include
     * @param string[] $exceptions
     * @return void
     */
    private function fill_params(&$returnArray, $category, $include, $exceptions)
    {

        switch($category){
            case Active_Get_Params::GET:
                $master = $_GET;
                break;
            case Active_Get_Params::POST:
                $master = $_POST;
                break;
            default:
                $master = [];
                break;
        }

        if($include === true){
            foreach ($master as $key => $value){
                if(!in_array($key, $exceptions)){
                    $returnArray[$key] = $value;
                }
            }
        }else{
            foreach ($include as $key){
                if(key_exists($key, $master)){
                    $returnArray[$key] = $master[$key];
                }
            }
        }

    }


    /**
     * @param Model|string $model
     * @param Attr|string $attr
     * @param string $prefix
     * @return ValidatedValue
     */
    public function validate($model, $attr, $prefix=null){

        /** @var Model $model */
        $model = Entities_Manager::go_getModel($model);
        $attr = $model->getAttr($attr);

        $validatedValue = $this->validateOnlyFormat($model, $attr, $prefix);

        if($validatedValue->isValid()){

            /** @var AttrExt_Validate|null $attrExt_Validate */
            $attrExt_Validate = $attr->findExtension(AttrExt_Validate::class);

            if($attrExt_Validate !== null){
                $validatedValue = $attrExt_Validate->validate($validatedValue);
            }

        }

        return $validatedValue;

    }

    /**
     * @param Model|string $model
     * @param Attr|string $attr
     * @param string $prefix
     * @return ValidatedValue
     */
    public function validateOnlyFormat($model, $attr, $prefix=null){
        /** @var Model $model */
        $model = Entities_Manager::go_getModel($model);
        $attr = $model->getAttr($attr);

        if(array_key_exists($model->getIdOrClass(), $this->postModels))
            $globalArray = $_POST;
        else if(array_key_exists($model->getIdOrClass(), $this->getModels))
            $globalArray = $_GET;
        else
            throw new DevPanic("No declared model as params: " . $model->getIdOrClass() . ".");

        if($attr instanceof Attr_String || $attr instanceof Attr_Int || $attr instanceof Attr_Decimal || $attr instanceof Attr_Bool || $attr instanceof Attr_Date){

            /** @var AttrExt_Param|null $attrExt_Param */
            $attrExt_Param = $attr->findExtension(AttrExt_Param::class);

            if($attrExt_Param != null){
                $name = $attrExt_Param->getName();
            }else{
                $name = $attr->getId();
            }
            if($prefix !== null)
                $name = $prefix . $name;

            if(array_key_exists($name, $globalArray)){
                return $attr->validate($globalArray[$name]);
            }else{
                return new ValidatedValue(false, true, null, "Undefined");
            }

        } else if($attr instanceof Attr_File){

            /** @var AttrExt_Param|null $attrExt_Param */
            $attrExt_Param = $attr->findExtension(AttrExt_Param::class);

            if($attrExt_Param != null){
                $name = $attrExt_Param->getName();
            }else{
                $name = $attr->getId();
            }
            if($prefix !== null)
                $name = $prefix . $name;

            $value = array_key_exists($name, $_FILES) ? $_FILES[$name] : null;

            if($value !== null){
                if (
                    !isset($value['error']) ||
                    is_array($value['error'])
                ) {
                    return new ValidatedValue(false, false, null, Attr_File::ERROR_BAD_FORMAT);
                }
                if($value['error'] == UPLOAD_ERR_NO_FILE)
                    $value = null;
            }

            return $attr->validate($value);

        } else{
            return new ValidatedValue(false);
        }
    }

    function multiple(array $_files, $top = TRUE)
    {
        $files = array();
        foreach($_files as $name=>$file){
            if($top) $sub_name = $file['name'];
            else    $sub_name = $name;

            if(is_array($sub_name)){
                foreach(array_keys($sub_name) as $key){
                    $files[$name][$key] = array(
                        'name'     => $file['name'][$key],
                        'type'     => $file['type'][$key],
                        'tmp_name' => $file['tmp_name'][$key],
                        'error'    => $file['error'][$key],
                        'size'     => $file['size'][$key],
                    );
                    $files[$name] = $this->multiple($files[$name], FALSE);
                }
            }else{
                $files[$name] = $file;
            }
        }
        return $files;
    }

    /**
     * @param string $name
     * @return ValidatedValue
     */
    public function validateStringPost($name)
    {

        $globalArray = $_POST;

        $value = array_key_exists($name, $globalArray) ? $globalArray[$name] : null;

        if(is_string($value) || is_numeric($value)){
            return new ValidatedValue(true, true, strval($value));
        }else if(is_null($value)){
            return new ValidatedValue(true, true, null);
        }

        return new ValidatedValue(false);

    }

    public function validateStringPost_NameStartingWith_ReturnEnding($prefix)
    {

        $globalArray = $_POST;

        foreach ($globalArray as $name => $value){

            $prefixLength = strlen($prefix);

            // php <8 starts_with
            if(strncmp($name, $prefix, $prefixLength) === 0){
                $actionLength = strlen($name) - $prefixLength;
                if($actionLength === 0)
                    return new ValidatedValue(true, true, null);

                $action = substr( $name, $prefixLength, strlen($name) - $prefixLength);

                return new ValidatedValue(true, true, strval($action));
            }

        }

        return new ValidatedValue(false);

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
        return $varManager->validate($model, $attr);
    }

    /**
     * @param callable|null $builder
     */
    public static function add($builder)
    {
        GlobalContext::add(new Manager_Params($builder));
    }

    /**
     * @param Model|class-string $model
     * @param Attr|string $attr
     * @return Param
     */
    public static function go_Param_ModelAttr($model, $attr){
        $a = new Active_Param_Value(Active_Param_Abstract::TYPE_STRING, $model, $attr);
        GlobalContext::add($a);
        return $a;
    }

    public static function go_addModel_GET($class)
    {
        /** @var Manager_Params $manager */
        $manager = Find::sync(Manager_Params::class);
        $manager->addModel_GET($class);

    }

    public static function go_addModel_POST($class)
    {
        /** @var Manager_Params $manager */
        $manager = Find::sync(Manager_Params::class);
        $manager->addModel_POST($class);

    }


}