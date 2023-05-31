<?php

namespace monolitum\bootstrap\values;

use monolitum\frontend\ElementComponent;

interface BSBuiltIntoInterface
{

    /**
     * @param ElementComponent $component
     * @return void
     */
    public function buildInto($component);

}