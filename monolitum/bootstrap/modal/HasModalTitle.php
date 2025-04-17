<?php

namespace monolitum\bootstrap\modal;

trait HasModalTitle
{
    /**
     * @var string|null
     */
    private $title = null;

    public function setTitle($title)
    {
        $this->title = $title;
    }

    abstract public function buildRenderable($active);

}
