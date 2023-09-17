<?php

namespace monolitum\bootstrap\style;

use monolitum\bootstrap\values\BSBuiltIntoInterface;
use monolitum\bootstrap\values\Responsive;
use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\ElementComponent_Ext;

class BSColSpanResponsive extends Responsive implements BSBuiltIntoInterface
{

    /**
     * @param BSColSpan|int $def
     */
    public function __construct($def)
    {
        parent::__construct(is_int($def) ? BSColSpan::c($def) : $def);
    }

    /**
     * @param BSColSpan|int $sm
     * @return BSColSpanResponsive
     */
    public function sm($sm)
    {
        if(is_int($sm)){
            return parent::sm(BSColSpan::c($sm)); // TODO: Change the autogenerated stub
        }else{
            return parent::sm($sm); // TODO: Change the autogenerated stub
        }
    }

    /**
     * @param BSColSpan|int $md
     * @return BSColSpanResponsive
     */
    public function md($md)
    {
        if(is_int($md)){
            return parent::md(BSColSpan::c($md)); // TODO: Change the autogenerated stub
        }else{
            return parent::md($md); // TODO: Change the autogenerated stub
        }
    }

    /**
     * @param BSColSpan|int $lg
     * @return BSColSpanResponsive
     */
    public function lg($lg)
    {
        if(is_int($lg)){
            return parent::lg(BSColSpan::c($lg)); // TODO: Change the autogenerated stub
        }else{
            return parent::lg($lg); // TODO: Change the autogenerated stub
        }
    }

    /**
     * @param BSColSpan|int $xl
     * @return BSColSpanResponsive
     */
    public function xl($xl)
    {
        if(is_int($xl)){
            return parent::xl(BSColSpan::c($xl)); // TODO: Change the autogenerated stub
        }else{
            return parent::xl($xl); // TODO: Change the autogenerated stub
        }
    }

    /**
     * @param BSColSpan|int $xxl
     * @return BSColSpanResponsive
     */
    public function xxl($xxl)
    {
        if(is_int($xxl)){
            return parent::xxl(BSColSpan::c($xxl)); // TODO: Change the autogenerated stub
        }else{
            return parent::xxl($xxl); // TODO: Change the autogenerated stub
        }
    }

    public function add(){
        GlobalContext::add(
            new ElementComponent_Ext(
                function (ElementComponent_Ext $it) {
                    $this->buildInto($it->getElementComponent());
                })
        );
    }

    /**
     * @param array<BSColSpanResponsive> $spans
     * @param int $count
     * @return BSColSpanResponsive
     */
    public static function computeComplement(array $spans, $count)
    {

        if(count($spans) === $count)
            return null;

        $def = 0;
        $sm = 0;
        $md = 0;
        $lg = 0;
        $xl = 0;
        $xxl = 0;

        foreach($spans as $span){
            $currentValue = 12;
            if($span->def !== null)
                $currentValue = $span->def->getValue();
            $def += $currentValue;

            $currentValue = 12;
            if($span->sm !== null)
                $currentValue = $span->sm->getValue();
            $sm += $currentValue;

            $currentValue = 12;
            if($span->md !== null)
                $currentValue = $span->md->getValue();
            $md += $currentValue;

            $currentValue = 12;
            if($span->lg !== null)
                $currentValue = $span->lg->getValue();
            $lg += $currentValue;

            $currentValue = 12;
            if($span->xl !== null)
                $currentValue = $span->xl->getValue();
            $xl += $currentValue;

            $currentValue = 12;
            if($span->xxl !== null)
                $currentValue = $span->xxl->getValue();
            $xxl += $currentValue;

        }

        $voidCount = $count - count($spans);
        $lastSpan = (12 - $def) / $voidCount;

        $toReturn = BSColSpanResponsive::of($lastSpan < 12 ? $lastSpan : null);

        $currentSpan = (12 - $sm) / $voidCount;
        if($currentSpan != $lastSpan)
            $toReturn->sm($currentSpan);
        $lastSpan = $currentSpan;

        $currentSpan = (12 - $md) / $voidCount;
        if($currentSpan != $lastSpan)
            $toReturn->md($currentSpan);
        $lastSpan = $currentSpan;

        $currentSpan = (12 - $lg) / $voidCount;
        if($currentSpan != $lastSpan)
            $toReturn->lg($currentSpan);
        $lastSpan = $currentSpan;

        $currentSpan = (12 - $xl) / $voidCount;
        if($currentSpan != $lastSpan)
            $toReturn->xl($currentSpan);
        $lastSpan = $currentSpan;

        $currentSpan = (12 - $xxl) / $voidCount;
        if($currentSpan != $lastSpan)
            $toReturn->xxl($currentSpan);

        return $toReturn;
    }

    /**
     * @param BSColSpan $def
     * @return BSColSpanResponsive
     */
    public static function of($def = null)
    {
        return new BSColSpanResponsive($def);
    }

    /**
     * @param ElementComponent $component
     * @param bool $inverted
     * @return void
     */
    public function buildInto($component, $inverted = false)
    {
        parent::_buildInto($component, "col", $inverted);
    }

}