<?php

namespace monolitum\backend\router;

use monolitum\core\Active;
use monolitum\core\Node;

abstract class Router_InstanceOf_Abstract extends Router_Abstract implements Active {

    /**
     * @param callable|null $builder
     */
    function __construct(callable $builder = null){
        parent::__construct($builder);
    }

    /**
     * @param $class string
     * @return Node|null
     */
    protected function select($class){
        if($class != null && array_key_exists($class, $this->map)){
            return $this->map[$class];
        }else{
            foreach ($this->map as $item => $value) {
                if(is_subclass_of($class, $item))
                    return $value;
            }
        }
        return $this->default_route;
    }

}
