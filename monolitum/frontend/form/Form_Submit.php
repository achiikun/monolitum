<?php

namespace monolitum\frontend\form;

use monolitum\backend\params\Link;
use monolitum\backend\params\Path;
use monolitum\backend\res\HrefResolver;
use monolitum\backend\res\HrefResolver_Impl;
use monolitum\core\Find;
use monolitum\entity\attr\Attr;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;

abstract class Form_Submit extends ElementComponent
{

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var Link|Path
     */
    protected $link;

    /**
     * @var HrefResolver
     */
    protected $linkResolver;


    // Method is defined in the form
//    /**
//     * @var string
//     */
//    protected $method = null;

    /**
     * @var callable
     */
    private $onValidated = null;

    /**
     * @param HtmlElement $element
     * @param Attr|string $attrid
     * @param callable|null $builder
     */
    public function __construct($element, $builder = null)
    {
        parent::__construct($element, $builder);
    }

//    public function setMethodGET()
//    {
//        $this->method = "get";
//    }
//
//    public function setMethodPOST()
//    {
//        $this->method = "post";
//    }
//
//    /**
//     * @return string|null
//     */
//    public function getMethod()
//    {
//        return $this->method;
//    }

    /**
     * @return string|null
     */
    public function getFinalCustomFormMethod()
    {
        return $this->form->_getSubmitMethod($this);
    }

    /**
     * @return HrefResolver
     */
    public function getFinalCustomLinkResolver()
    {
        if($this->linkResolver !== null)
            return $this->linkResolver;
        return $this->form->_getSubmitLinkResolver($this);
    }

    /**
     * @return string
     */
    public function getFinalName()
    {

        $prefix = $this->form->_getSubmitPrefix($this);
        $action = $this->getAction();

        return ($prefix !== null ? $prefix : "") .  ($action !== null ? $action : "");
    }

    /**
     * @param Link|Path $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * callable(Form $form, string $action)
     * @param callable $onValidated
     */
    public function setOnValidated($onValidated)
    {
        $this->onValidated = $onValidated;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return callable|null
     */
    public function getOnValidated()
    {
        return $this->onValidated;
    }

    protected function buildNode()
    {
        $this->form = Find::sync(Form::class);
        $this->form->_registerFormSubmit($this);

        parent::buildNode();
    }

    protected function afterBuildNode()
    {
        parent::afterBuildNode();
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Called by the form, when it's just built and validated
     * @return void
     */
    abstract public function afterBuildForm();

}