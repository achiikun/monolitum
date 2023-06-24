<?php

namespace monolitum\bootstrap;

use monolitum\bootstrap\values\BSColSpan;
use monolitum\bootstrap\values\BSColSpanResponsive;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class Ext_Col extends ElementComponent_Ext
{

    /**
     * @var int|BSColSpanResponsive
     */
    private $span;

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param int|BSColSpan|BSColSpanResponsive $size
     * @return $this
     */
    public function span($size) {
        $this->span = $size;
        return $this;
    }

    public function apply()
    {
        parent::apply();

        $elementComponent = $this->getElementComponent();
        if($this->span != null){
            if($this->span instanceof BSColSpanResponsive){
                $this->span->buildInto($elementComponent);
            }else if($this->span instanceof BSColSpan){
                $this->span->buildInto($elementComponent);
            }else{
                $elementComponent->addClass("col-" . $this->span);
            }
        }
        else
            $elementComponent->addClass("col");

    }

    public static function add($builder = null){
        $it = new Ext_Col($builder);
        GlobalContext::add($it);
        return $it;
    }

}