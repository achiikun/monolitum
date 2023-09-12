<?php

namespace monolitum\bootstrap\style;

class BSDisplay_Flex extends BSDisplay
{

    /**
     * @var BSJustifyContent
     */
    private $justifyContent = null;

    private $row = null;
    private $reverse = null;

    /**
     * @param string $value
     */
    function __construct($value)
    {
        parent::__construct($value);
    }

    /**
     * @param bool $reverse
     * @return $this
     */
    public function row($reverse = false){
        $this->row = true;
        $this->reverse = $reverse;
        return $this;
    }

    /**
     * @param bool $reverse
     * @return $this
     */
    public function col($reverse = false){
        $this->row = false;
        $this->reverse = $reverse;
        return $this;
    }

    /**
     * @param BSJustifyContent $justifyContent
     * @return BSDisplay_Flex
     */
    public function justifyContent($justifyContent)
    {
        $this->justifyContent = $justifyContent;
        return $this;
    }

    public function buildInto($component, $inverted = false)
    {
        parent::buildInto($component, $inverted);

        if($this->row !== null){
            if($this->row){
                if($this->reverse)
                    $component->addClass("flex-row-reverse");
                else
                    $component->addClass("flex-row");
            }else{

                if($this->reverse)
                    $component->addClass("flex-column-reverse");
                else
                    $component->addClass("flex-column");
            }
        }

        if($this->justifyContent !== null)
            $this->justifyContent->buildInto($component);

    }

    public function buildIntoResponsive($component, $responsiveValue, $inverted = false)
    {
        parent::buildIntoResponsive($component, $responsiveValue, $inverted);

        if($this->row !== null){
            if($this->row){
                if($this->reverse)
                    $component->addClass("flex-" . $responsiveValue . "-row-reverse");
                else
                    $component->addClass("flex-" . $responsiveValue . "-row");
            }else{

                if($this->reverse)
                    $component->addClass("flex-" . $responsiveValue . "-column-reverse");
                else
                    $component->addClass("flex-" . $responsiveValue . "-column");
            }
        }

        if($this->justifyContent !== null)
            $this->justifyContent->buildIntoResponsive($component, $responsiveValue);

    }

}