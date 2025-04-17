<?php

namespace monolitum\bootstrap\modal;

trait HasModalId
{

    /**
     * @var string
     */
    private $modalId;

    /**
     * @return string
     */
    public function getModalId()
    {
        return $this->modalId;
    }

}
