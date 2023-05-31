<?php

namespace monolitum\wangeditor;

use monolitum\bootstrap\Form_Attr;
use monolitum\core\GlobalContext;

class Form_Attr_WangEditor extends Form_Attr
{

    public function __construct($attrid, $builder = null)
    {
        parent::__construct($attrid, $builder);
    }

    protected function createFormControl()
    {
        return new WangEditor(function (WangEditor $it) {
            $it->setId($this->getName());
            $it->setName($this->getName());
            if($this->hasValue())
                $it->setValue($this->getValue());

            if($this->disabled !== null ? $this->disabled : $this->getForm()->isDisabled())
                $it->setDisabled(true);

        });
    }

    /**
     * @param string $attrid
     * @param callable|null $builder
     * @return Form_Attr_WangEditor
     */
    public static function add($attrid, $builder = null)
    {
        $fc = new Form_Attr_WangEditor($attrid, $builder);
        GlobalContext::add($fc);
        return $fc;
    }

}