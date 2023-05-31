<?php

namespace monolitum\frontend\component;

use monolitum\core\GlobalContext;
use monolitum\frontend\Component;

class Reference extends Component {

    /**
     * @param callable|null $builder
     */
    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param callable|null $builder
     * @return Component
     */
    public static function addEmpty($builder = null)
    {
        $c = new Reference($builder);
        GlobalContext::add($c);
        return $c;
    }

    public static function ofEmpty($builder = null)
    {
        return new Reference($builder);
    }

}