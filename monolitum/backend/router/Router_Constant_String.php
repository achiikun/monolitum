<?php

namespace monolitum\backend\router;

use monolitum\core\GlobalContext;
use monolitum\core\Node;
use monolitum\backend\params\Param;
use monolitum\core\Renderable_Node;

class Router_Constant_String extends Router_Constant_Abstract {

    /**
     * @var Node
     */
    private $selected;

    /**
     * @var Param
     */
    private $param;

    /**
     * @var callable
     */
    private $onSelected;

    /**
     * @param Param $param
     * @param callable $builder
     */
    function __construct($param, $builder = null){
        parent::__construct($builder);
        $this->param = $param;
    }

    /**
     * @param callable $onSelected
     */
    public function setOnSelected($onSelected)
    {
        $this->onSelected = $onSelected;
    }

    /**
     * @param string $value
     * @param Node|callable $router
     */
    public function routeValue($value, $router){
        $this->map[$value] = $router;
    }

    protected function buildNode(){
        parent::buildNode();

        $validatedValue = $this->param->getValidatedValue();

        if($validatedValue->isValid()){

            $this->selected = $this->select(
                $validatedValue->getValue()
            );

        }else{

            $this->selected = $this->select(
                ''
            );

        }

        if($this->selected == null){
            throw new Panic_NothingSelected();
        }else{

            if($this->selected instanceof Renderable_Node){
                $this->receiveActive($this->selected);
                //$this->selected->_build($this->getContext(), $this);
            }else if(is_callable($this->selected)){
                $c = $this->selected;
                $c();
            }

            if($this->onSelected != null){
                $s = $this->onSelected;
                $s($this->selected);
            }

        }

    }

//    protected function executeRouter(){
//        if($this->selected instanceof Node){
//            $this->executeChild($this->selected);
//        }
//    }

    /**
     * @param Param $param
     * @param callable $builder
     */
    public static function add($param, callable $builder)
    {
        GlobalContext::add(new Router_Constant_String($param, $builder));
    }

}
