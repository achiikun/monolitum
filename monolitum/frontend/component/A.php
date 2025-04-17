<?php

namespace monolitum\frontend\component;

use monolitum\backend\params\Link;
use monolitum\backend\params\Path;
use monolitum\backend\res\Active_Create_HrefResolver;
use monolitum\backend\res\HrefResolver;
use monolitum\backend\res\HrefResolver_String;
use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\LinkHook;

class A extends AbstractText
{

    /**
     * @var LinkHook|Link|Path|string
     */
    private $href;
    /**
     * @var HrefResolver
     */
    private $hrefResolver;

    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement("a"), $builder);
    }

    /**
     * @param LinkHook|Link|Path|string $href
     */
    public function setHref($href)
    {
        $this->href = $href;
    }

    protected function afterBuildNode()
    {

        if($this->href){
            if(is_string($this->href)){
                $this->hrefResolver = new HrefResolver_String($this->href);
            }else if($this->href instanceof LinkHook){
                $this->href->buildLinkHook($this, $this->getElement());
            }else{
                $active = new Active_Create_HrefResolver($this->href);
                GlobalContext::add($active);
                $this->hrefResolver = $active->getHrefResolver();
            }
        }

        parent::afterBuildNode();
    }

    public function render()
    {
        $a = $this->getElement();

        if($this->hrefResolver){
            $a->setAttribute("href", $this->hrefResolver->resolve());
        }else if($this->href instanceof LinkHook){
            $this->href->renderLinkHook($this, $this->getElement());
        }else{
            $a->setAttribute("href", "#");
        }

        return parent::render();
    }

    /**
     * @param string|Renderable_Node $content
     * @param LinkHook|Link|Path|string $href
     * @return A
     */
    public static function fromContent($content, $href)
    {
        $fc = new A();
        $fc->append($content);
        $fc->setHref($href);
        return $fc;
    }

    /**
     * @param callable $builder
     * @return A
     */
    public static function of($builder = null)
    {
        return new A($builder);
    }

    /**
     * @param callable $builder
     * @return A
     */
    public static function add($builder = null)
    {
        $fc = new A($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}
