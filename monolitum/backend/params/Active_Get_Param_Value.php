<?php

namespace monolitum\backend\params;

use monolitum\entity\attr\Attr;
use monolitum\entity\Model;

class Active_Get_Param_Value extends Active_Abstract_ValidatedValue
{
    /**
     * @var string|Model
     */
    private $model;

    /**
     * @var string|Attr
     */
    private $attr;

    /**
     * @param string $type
     * @param string|Model $model
     * @param string|Attr $attr
     */
    public function __construct($type, $model, $attr)
    {
        parent::__construct($type);
        $this->model = $model;
        $this->attr = $attr;
    }

    /**
     * @return Model|string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return Attr|string
     */
    public function getAttr()
    {
        return $this->attr;
    }

}
