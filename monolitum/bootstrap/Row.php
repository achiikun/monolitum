<?php

namespace monolitum\bootstrap;

use monolitum\bootstrap\style\BSRow;
use monolitum\core\GlobalContext;
use monolitum\frontend\component\Div;

class Row extends Div
{

    /**
     * @var BSRow
     */
    private $ext;

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    protected function buildNode()
    {
        $this->ext = BSRow::add();
        parent::buildNode();
    }

    /**
     * @param int $value
     * @return $this
     */
    public function gap($value)
    {
        $this->ext->gap($value);
        return $this;
    }

    /**
     * @param callable $builder
     * @return Row
     */
    public static function add($builder = null)
    {
        $fc = new Row($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}