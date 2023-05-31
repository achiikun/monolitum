<?php

namespace monolitum\frontend\component;

use monolitum\core\GlobalContext;
use monolitum\frontend\Rendered;
use monolitum\frontend\html\HtmlElement;

class Meta extends Head{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $content;

    /**
     * @param string $name
     * @param string $content
     * @param $builder
     */
    public function __construct($name, $content, $builder = null)
    {
        parent::__construct($builder);
        $this->name = $name;
        $this->content = $content;
    }

    public function render()
    {
        $link = new HtmlElement("meta");
        $link->setAttribute("name", $this->name);
        $link->setAttribute("content", $this->content);
        
        return Rendered::of($link);
    }

    /**
     * @param string $name
     * @param string $content
     */
    public static function add($name, $content)
    {
        GlobalContext::add(new Meta($name, $content));
    }
    
}