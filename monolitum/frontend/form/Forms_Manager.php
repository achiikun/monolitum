<?php

namespace monolitum\frontend\form;

use monolitum\backend\Manager;
use monolitum\core\Active;
use monolitum\core\GlobalContext;
use monolitum\core\panic\DevPanic;

class Forms_Manager extends Manager implements Active
{

    /**
     * @var array<string, Form>
     */
    private $forms = [];

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param $form Form
     */
    public function registerForm($form){
        $formId = $form->getFormId();
        if($formId === null)
            throw new DevPanic("Form without id is registered.");
        if(array_key_exists($formId, $this->forms))
            throw new DevPanic("Form '$formId' is present twice.");
        $this->forms[$formId] = $form;
    }

    public function getForm($formId){
        if(array_key_exists($formId, $this->forms))
            return $this->forms[$formId];
    }

    /**
     * @param callable $builder
     * @return Forms_Manager
     */
    public static function add($builder)
    {
        $fc = new Forms_Manager($builder);
        GlobalContext::add($fc);
        return $fc;
    }

    function onNotReceived()
    {
        // TODO: Implement onNotReceived() method.
    }
}