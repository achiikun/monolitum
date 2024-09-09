<?php

namespace monolitum\frontend\component;

use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\core\ts\TS;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\Rendered;

class Title extends Renderable_Node implements Head{

    /**
     * @var string|TS
     */
    private $string;

    public function __construct($string, $builder = null)
    {
        parent::__construct($builder);
        $this->string = $string;
    }

    protected function afterBuildNode()
    {
        $this->string = TS::unwrapAuto($this->string);
    }

    public function render()
    {
        return Rendered::of(
            new HtmlElement("title", $this->string)
        );
    }

    /**
     * @param string|TS $string
     */
    public static function addString($string){
        GlobalContext::add(new Title($string));
    }


}
