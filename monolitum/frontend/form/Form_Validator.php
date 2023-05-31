<?php

namespace monolitum\frontend\form;

use monolitum\entity\attr\Attr;
use monolitum\entity\Entities_Manager;
use monolitum\entity\Model;
use monolitum\entity\ValidatedValue;
use monolitum\core\Find;
use monolitum\frontend\form\AttrExt_Form;
use monolitum\backend\params\Manager_Params;
use monolitum\backend\params\Validator;

class Form_Validator implements Validator
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @param Validator $validator
     */
    public function __construct($validator)
    {
        $this->validator = $validator;
    }

    function validate($model, $attr)
    {
        $model = Entities_Manager::go_getModel($model);
        $attr = $model->getAttr($attr);

        $validated = $this->validator->validate($model, $attr);
        if(!$validated->isValid())
            return $validated;
        $ext = $attr->findExtension(AttrExt_Form::class);
        if(!$ext)
            return $validated;

        return $ext->revalidate($validated);
    }

    function validateStringPost($name)
    {
        return $this->validator->validateStringPost($name);
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

        $ext = $attr->findExtension(AttrExt_Form::class);
        if(!$ext)
            return $validated;

        return $ext->revalidate($validated);

    }

}