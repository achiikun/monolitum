<?php

namespace monolitum\bootstrap;

use monolitum\bootstrap\values\BSColor;
use monolitum\core\GlobalContext;
use monolitum\backend\params\Link;
use monolitum\backend\params\Path;
use monolitum\core\Renderable_Node;
use monolitum\backend\res\Active_Resolve_Href;
use monolitum\backend\res\HrefResolver;
use monolitum\frontend\html\HtmlElement;

class Button extends AbstractText
{

    /**
     * @var Link|Path
     */
    private $href;
    /**
     * @var HrefResolver
     */
    private $hrefResolver;

    /**
     * @var bool
     */
    private $disabled = false;

    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement(null), $builder);
    }

    /**
     * @param BSColor $color
     * @return $this
     */
    public function color($color, $outline = false){
        if($outline)
            $this->addClass("btn-outline-" . $color->getValue());
        else
            $this->addClass("btn-" . $color->getValue());
        return $this;
    }

    /**
     * @param Link|Path $href
     * @return $this
     */
    public function setHref($href)
    {
        $this->href = $href;

        $active = new Active_Resolve_Href($this->href);
        GlobalContext::add($active);
        $this->hrefResolver = $active->getHrefResolver();

        return $this;
    }

    /**
     * @param bool $disabled
     * @return $this
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
        return $this;
    }

    public function render()
    {
        $a = $this->getElement();

        //TODO if it is JS action, set as button
        if(!$this->hrefResolver){
            $a->setTag("button");
            $a->setAttribute("type", "button");
        }else {
            $a->setTag("a");
            $a->setAttribute("role", "button");
            $a->setAttribute("href", $this->hrefResolver->resolve());
        }

        $this->addClass("btn");

        if($this->disabled){
            $this->addClass("disabled");
            $a->setAttribute("aria-disabled", "true");
        }

        return parent::render(); // TODO: Change the autogenerated stub
    }

    /**
     * @param string|Renderable_Node $content
     * @return Button
     */
    public static function of($content)
    {
        $fc = new Button();
        $fc->append($content);
        return $fc;
    }

    /**
     * @param callable $builder
     * @return Button
     */
    public static function build($builder = null)
    {
        return new Button($builder);
    }

    /**
     * @param callable $builder
     * @return Button
     */
    public static function add($builder = null)
    {
        $fc = new Button($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}