<?php

namespace monolitum\frontend\component;

use monolitum\frontend\Rendered;
use monolitum\core\Renderable_Node;
use monolitum\core\panic\DevPanic;

class Text extends Renderable_Node
{

    /**
     * @var string
     */
    private $string;

    public function __construct($string, $builder = null)
    {
        parent::__construct($builder);
        $this->string = $string;
    }

    public static function from($string)
    {
        return new Text($string);
    }

    public function onNotReceived()
    {
        throw new DevPanic();
    }

    public function render()
    {
        return Rendered::of($this->string);
    }

}
