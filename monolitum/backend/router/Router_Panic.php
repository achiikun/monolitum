<?php

namespace monolitum\backend\router;

use monolitum\core\GlobalContext;
use monolitum\core\Node;
use monolitum\core\PanicRouter;

class Router_Panic extends Router_InstanceOf_Abstract implements PanicRouter{

    /**
     * @var Node|null
     */
    private $selected;

    function __construct(callable $builder){
        parent::__construct($builder);
    }

    /**
     * @param string $panic
     * @param Node|callable $router
     */
    public function routePanic($panic, $router)
    {
        $this->map[$panic] = $router;
    }

    protected function buildNode(){
        parent::buildNode();

        $panic = $this->getContext()->getLastPanic();

        $this->selected = $this->select(
            get_class($panic)
        );

        if($this->selected == null){
            throw $panic;
        }else{
            if($this->selected instanceof Node){
                $this->selected->_build($this->getContext(), $this);
            }else if(is_callable($this->selected)){
                $c = $this->selected;
                $c();
            }
        }

    }

    protected function executeNode(){
        if($this->selected instanceof Node){
            $this->executeChild($this->selected);
        }
    }

    public static function add(callable $builder)
    {
        GlobalContext::add(new Router_Panic($builder));
    }

    /**
     * @param callable $builder
     * @return Router_Panic
     */
    public static function from(callable $builder)
    {
        return new Router_Panic($builder);
    }

}

