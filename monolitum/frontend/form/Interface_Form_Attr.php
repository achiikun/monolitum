<?php

namespace monolitum\frontend\form;

interface Interface_Form_Attr
{

    /**
     * Called by the form, when it's just built and validated
     * @return void
     */
    public function afterBuildForm();

}