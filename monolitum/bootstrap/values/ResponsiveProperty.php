<?php

namespace monolitum\bootstrap\values;

use monolitum\frontend\ElementComponent;

abstract class ResponsiveProperty
{

    /**
     * @param ElementComponent $component
     * @param string $prefix
     * @return void
     */
    public function _buildInto($component, $prefix, $inverted = false){
        $component->addClass($prefix . "-" . $this->getValue($inverted));
    }

    /**
     * @param ElementComponent $component
     * @param bool $inverted
     * @return void
     */
    public abstract function buildInto($component, $inverted = false);

    /**
     * @return string
     */
    public abstract function getValue($inverted = false);

}