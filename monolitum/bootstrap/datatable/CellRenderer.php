<?php

namespace monolitum\bootstrap\datatable;

use monolitum\entity\Entity;
use monolitum\frontend\Rendered;
use monolitum\core\Renderable_Node;

interface CellRenderer
{

    /**
     * @param DataTable $datatable
     * @return void
     */
    function prepare($datatable);

    /**
     * @param Entity $entity
     * @return Renderable_Node|Rendered
     */
    function render($entity);

}