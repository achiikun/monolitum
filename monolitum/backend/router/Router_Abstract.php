<?php

namespace monolitum\backend\router;

use monolitum\core\Active;
use monolitum\core\Node;
use monolitum\core\Renderable_Node;

abstract class Router_Abstract extends Renderable_Node implements Active {

    /**
     * @var array<mixed, Node|callable>
     */
    protected $map = [];

    /**
     * @var Node
     */
    protected $default_route;

    /**
     * @param callable|null $builder
     */
    function __construct(callable $builder = null){
        parent::__construct($builder);
    }

    /**
     *
     * @param Node $router
     */
    public function routeDefault($router){
        $this->default_route = $router;
    }

    public function onNotReceived()
    {
        // TODO: Implement onNotReceived() method.
    }

}
