<?php

namespace monolitum\bootstrap\datatable;

use monolitum\core\Active;
use monolitum\core\GlobalContext;
use monolitum\core\panic\DevPanic;

class DataTable_Col implements Active
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var CellRenderer|callable
     */
    private $renderer;

    /**
     * @var bool
     */
    private $sortable;

    /**
     * @var string
     */
    private $sortable_id;

    /**
     * @param $string
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param CellRenderer|callable $renderer
     * @return $this;
     */
    public function renderer($renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function sortable($id)
    {
        $this->sortable = true;
        $this->sortable_id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return CellRenderer|callable
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @return bool
     */
    public function isSortable()
    {
        return $this->sortable;
    }

    /**
     * @return string
     */
    public function getSortableId()
    {
        return $this->sortable_id;
    }

    function onNotReceived()
    {
        throw new DevPanic();
    }

    public static function add($string)
    {
        $col = new DataTable_Col($string);
        GlobalContext::add($col);
        return $col;
    }

}