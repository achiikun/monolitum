<?php

namespace monolitum\bootstrap\values;

use monolitum\frontend\ElementComponent;

abstract class Responsive
{

    /**
     * @var ResponsiveProperty
     */
    protected $def;

    /**
     * @var ResponsiveProperty
     */
    protected $sm;

    /**
     * @var ResponsiveProperty
     */
    protected $md;

    /**
     * @var ResponsiveProperty
     */
    protected $lg;

    /**
     * @var ResponsiveProperty
     */
    protected $xl;

    /**
     * @var ResponsiveProperty
     */
    protected $xxl;

    /**
     * @param ResponsiveProperty $default
     */
    public function __construct($default)
    {
        $this->def = $default;
    }

    /**
     * @param ResponsiveProperty $sm
     * @return Responsive
     */
    public function sm($sm)
    {
        $this->sm = $sm;
        return $this;
    }

    /**
     * @param ResponsiveProperty $md
     * @return Responsive
     */
    public function md($md)
    {
        $this->md = $md;
        return $this;
    }

    /**
     * @param ResponsiveProperty $lg
     * @return Responsive
     */
    public function lg($lg)
    {
        $this->lg = $lg;
        return $this;
    }

    /**
     * @param ResponsiveProperty $xl
     * @return Responsive
     */
    public function xl($xl)
    {
        $this->xl = $xl;
        return $this;
    }

    /**
     * @param ResponsiveProperty $xxl
     * @return Responsive
     */
    public function xxl($xxl)
    {
        $this->xxl = $xxl;
        return $this;
    }

    /**
     * @param ElementComponent $component
     * @param string $prefix
     * @return void
     */
    public function _buildInto($component, $prefix, $inverted = false){

        if($this->def != null)
            $component->addClass($prefix . "-" . $this->def->getValue($inverted));

        if($this->sm != null)
            $component->addClass($prefix . "-sm-" . $this->sm->getValue($inverted));

        if($this->md != null)
            $component->addClass($prefix . "-md-" . $this->md->getValue($inverted));

        if($this->lg != null)
            $component->addClass($prefix . "-lg-" . $this->lg->getValue($inverted));

        if($this->xl != null)
            $component->addClass($prefix . "-xl-" . $this->xl->getValue($inverted));

        if($this->xxl != null)
            $component->addClass($prefix . "-xxl-" . $this->xl->getValue($inverted));

    }

}