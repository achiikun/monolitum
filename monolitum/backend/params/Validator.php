<?php

namespace monolitum\backend\params;

use monolitum\entity\attr\Attr;
use monolitum\entity\Model;
use monolitum\entity\ValidatedValue;

interface Validator
{

    /**
     * @param Model|class-string $model
     * @param Attr|string $attr
     * @return ValidatedValue
     */
    function validate($model, $attr, $prefix=null);

    /**
     * @param string $name
     * @return ValidatedValue
     */
    function validateStringPost($name);

    /**
     * @param string $name
     * @return ValidatedValue
     */
    function validateStringPost_NameStartingWith_ReturnEnding($prefix);

}