<?php

namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;

class Row extends Div
{

    /**
     * @var Ext_Row
     */
    private $ext;

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    protected function buildNode()
    {
        $this->ext = Ext_Row::add();
        parent::buildNode();
    }

    /**
     * @param int $value
     * @return $this
     */
    public function gap($value)
    {
        $this->addClass("gap-" . $value);
        return $this;
    }

    /**
     * @param callable $builder
     * @return Div
     */
    public static function add($builder = null)
    {
        $fc = new Row($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}