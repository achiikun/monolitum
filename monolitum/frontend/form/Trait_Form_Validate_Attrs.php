<?php

namespace monolitum\frontend\form;

trait Trait_Form_Validate_Attrs
{
    protected $validate_attrs_hasBeenSet = false;

    /**
     * @var bool
     */
    protected $validate_attrs_all = true;

    /**
     * @var array<string>
     */
    protected $validate_attrs = [];

    /**
     * @param string ...$attrs
     * @return void
     */
    public function validate_all_except(...$attrs){
        $this->validate_attrs_hasBeenSet = true;
        $this->validate_attrs_all = true;
        $this->validate_attrs = $attrs;
    }

    /**
     * @param string ...$attrs
     * @return void
     */
    public function validate_only(...$attrs){
        $this->validate_attrs_hasBeenSet = true;
        $this->validate_attrs_all = false;
        $this->validate_attrs = $attrs;
    }

}
