<?php

namespace monolitum\backend\router;

use monolitum\core\Active;
use monolitum\core\Node;

abstract class Router_Constant_Abstract extends Router_Abstract implements Active {

    /**
     * @param callable|null $builder
     */
    function __construct(callable $builder = null){
        parent::__construct($builder);
    }

    /**
     * @param $value
     * @return Node|null
     */
    protected function select($value){

        if($value == null){
            if(array_key_exists("", $this->map))
                return $this->map[""];
            else if($this->default_route != null){
                return $this->default_route;
            }else{
                return null;
            }
        }else if(array_key_exists($value, $this->map)){
            return $this->map[$value];
        }else{
            if($this->default_route != null){
                return $this->default_route;
            }else{
                return null;
            }
        }

    }

}
