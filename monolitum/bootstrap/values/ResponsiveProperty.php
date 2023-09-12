<?php

namespace monolitum\bootstrap\values;

use monolitum\frontend\ElementComponent;

interface ResponsiveProperty
{

    /**
     * @param ElementComponent $component
     * @param string $responsiveValue
     * @param bool $inverted
     * @return void
     */
    public function buildIntoResponsive($component, $responsiveValue, $inverted = false);

    /**
     * @return string
     */
    public function getValue($inverted = false);

}