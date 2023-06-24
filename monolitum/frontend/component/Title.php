<?php

namespace monolitum\frontend\component;

use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\Rendered;

class Title extends Renderable_Node implements Head{

    /**
     * @var string
     */
    private $string;

    public function __construct($string, $builder = null)
    {
        parent::__construct($builder);
        $this->string = $string;
    }

    public function render()
    {
        return Rendered::of(
            new HtmlElement("title", $this->string)
        );
    }

    /**
     * @param string $string
     */
    public static function addString($string){
        GlobalContext::add(new Title($string));
    }
    
    
}