<?php

namespace monolitum\bootstrap\style;

use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class BSCol extends ElementComponent_Ext
{

    public function __construct($span)
    {
        parent::__construct(function (BSCol $it) use ($span) {

            $elementComponent = $it->getElementComponent();
            if($span != null){
                if($span instanceof BSColSpanResponsive){
                    $span->buildInto($elementComponent);
                }else if($span instanceof BSColSpan){
                    $span->buildInto($elementComponent);
                }else{
                    $elementComponent->addClass("col-" . $span);
                }
            }
            else
                $elementComponent->addClass("col");

        });
    }

    /**
     * @param int|BSColSpan|BSColSpanResponsive $span
     * @return BSCol
     */
    public static function span($span){
        return new BSCol($span);
    }

    /**
     * @param int|BSColSpan|BSColSpanResponsive $span
     * @return $this
     */
    public static function addSpan($span){
        GlobalContext::add(new BSCol($span));
    }

}