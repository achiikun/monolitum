<?php

namespace monolitum\bootstrap\modal;

use monolitum\frontend\LinkHook;

class ModalToggleLinkHook implements LinkHook
{

    private $modal;

    /**
     * @param HasModalId $modal
     */
    function __construct($modal)
    {
        $this->modal = $modal;
    }

    function buildLinkHook($component, $element = null)
    {

    }

    function renderLinkHook($component, $element)
    {
        $element->setAttribute("data-bs-toggle", "modal");
        $element->setAttribute("href", "#" . $this->modal->getModalId());
    }
}
