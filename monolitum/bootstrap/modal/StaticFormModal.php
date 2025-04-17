<?php

namespace monolitum\bootstrap\modal;

use monolitum\backend\globals\Active_Request_NewId;
use monolitum\backend\params\Manager_Params;
use monolitum\core\Find;
use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\entity\AnonymousModel;
use monolitum\entity\Model;
use monolitum\frontend\form\Form;
use monolitum\frontend\form\Form_Validator;
use monolitum\frontend\form\Form_Validator_Entity;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\LinkHook;
use monolitum\frontend\Rendered;

/*
 * <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Modal 1</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Show a second modal and hide this one with the button below.
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal">Open second modal</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Modal 2</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Hide this modal and show the first with the button below.
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-toggle="modal">Back to first</button>
      </div>
    </div>
  </div>
</div>
<a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModalToggle" role="button">Open first modal</a>
 */

class StaticFormModal extends Form
{

    use HasModalHeader;
    use HasModalFooter;
    use HasModalId;

    /**
     * @param Form_Validator|null $validator
     * @param string $formId
     * @param callable|null $builder
     */
    public function __construct($validator, $formId, $builder = null)
    {
        parent::__construct($validator, $formId, $builder);
    }

    /**
     * @return LinkHook
     */
    public function newLinkToToggleModal()
    {
        return new ModalToggleLinkHook($this);
    }

    protected function buildComponent()
    {
        $this->modalId = Active_Request_NewId::go_newId("modal");
    }

    public function render()
    {
        $formElement = $this->getFormElement();
        if($formElement === null){
            // A form parent exist, create our own
            $modalContent = new HtmlElement("div");
            $modalContent->addClass("modal-content");

            $parent = parent::render();
            $parent->renderTo($modalContent);

            return StaticModal::createModalElement($this->modalId, $parent);
        }else{
            $formElement->addClass("modal-content");
            return StaticModal::createModalElement($this->modalId, parent::render());
        }
    }

    public function renderChilds()
    {

        $modalHeader = $this->createModalHeaderElement();

        $modalBody = new HtmlElement("div");
        $modalBody->addClass("modal-body");

        Renderable_Node::renderRenderedTo(parent::renderChilds(), $modalBody);

        $modalFooter = $this->createModalFooterElement();

        return Rendered::of([
            $modalHeader,
            $modalBody,
            $modalFooter
        ]);
    }

    /**
     * Creates a Form using Manager_Params as provider and a Model as model.
     * @param class-string|Model|AnonymousModel $model
     * @param callable $builder
     * @return StaticFormModal
     */
    public static function addFromModel($model, $builder)
    {
        /** @var Manager_Params $manager_params */
        $manager_params = Find::sync(Manager_Params::class);
        $fc = new StaticFormModal(new Form_Validator_Entity(
            $manager_params,
            $model,
            true
        ), null, $builder);
        GlobalContext::add($fc);
        return $fc;
    }

    /**
     * Creates a Form using Manager_Params as provider and a Model as model.
     * @param class-string|Model $model
     * @param callable $builder
     * @return StaticFormModal
     */
    public static function addFromModelAndEntity($model, $entity, $builder)
    {
        /** @var Manager_Params $manager_params */
        $manager_params = Find::sync(Manager_Params::class);
        $fc = new StaticFormModal((new Form_Validator_Entity(
            $manager_params,
            $model,
            true
        ))->setCurrentEntity($entity), null, $builder);
        GlobalContext::add($fc);
        return $fc;
    }

    /**
     * Creates a Form using Manager_Params as provider and a Model as model.
     * @param class-string|Model $model
     * @param string $formId
     * @param callable $builder
     * @return StaticFormModal
     */
    public static function addFromModelAndId($model, $formId, $builder)
    {
        /** @var Manager_Params $manager_params */
        $manager_params = Find::sync(Manager_Params::class);
        $fc = new StaticFormModal(new Form_Validator_Entity(
            $manager_params,
            $model,
            true
        ), $formId, $builder);
        GlobalContext::add($fc);
        return $fc;
    }

    /**
     * Creates a Form without validator.
     * @param callable $builder
     * @return StaticFormModal
     */
    public static function addAnonymous($builder)
    {
        $fc = new StaticFormModal(null, null, $builder);
        $fc->setAnonymousAttributesNames();
        GlobalContext::add($fc);
        return $fc;
    }

}

// <a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModalToggle" role="button">Open first modal</a>
