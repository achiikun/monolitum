<?php

namespace monolitum\frontend\component;

use monolitum\backend\params\Path;
use monolitum\frontend\Component;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\html\HtmlElementContent;
use monolitum\frontend\Rendered;

class JSInlineScript extends Component {

    private $scripts = [];

    /**
     * @param Path $path
     * @param $builder
     */
    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param string $script
     * @return $this
     */
    public function addScript($script){
        $this->scripts[] = $script;
        return $this;
    }

    public function render()
    {
        $link = new HtmlElement("script");
        $link->setContent((new HtmlElementContent(implode("", $this->scripts)))->setRaw());
        
        return Rendered::of($link);
    }
    
}