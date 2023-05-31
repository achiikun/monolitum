<?php

namespace monolitum\bootstrap\values;

class BSDisplayFlex extends BSDisplay
{

    /**
     * @var BSJustifyContent|BSJustifyContentResponsive
     */
    private $justifyContent = null;

    /**
     * @param string $value
     */
    protected function __construct($value)
    {
        parent::__construct($value);
    }

    /**
     * @param BSJustifyContent|BSJustifyContentResponsive $justifyContent
     * @return BSDisplayFlex
     */
    public function justifyContent($justifyContent)
    {
        $this->justifyContent = $justifyContent;
        return $this;
    }

    public function buildInto($component, $inverted = false)
    {
        parent::_buildInto($component, "d", $inverted);

        if($this->justifyContent !== null)
            $this->justifyContent->buildInto($component);

    }

}