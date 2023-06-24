<?php

namespace monolitum\frontend\component;

use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\frontend\Rendered;
use monolitum\backend\params\Path;
use monolitum\backend\res\Active_Resolve_Res;
use monolitum\backend\res\ResResolver;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\html\HtmlElementContent;

class JSScript extends Renderable_Node implements Head{

    /**
     * @var Path
     */
    private $path;

    /**
     * @var ResResolver
     */
    private $pathResolver;

    /**
     * @var null|string|boolean
     */
    private $module;

    /**
     * @var boolean
     */
    private $async;

    /**
     * @param Path $path
     * @param $builder
     */
    public function __construct($path, $module = null, $async = false, $builder = null)
    {
        parent::__construct($builder);
        $this->module = $module;
        $this->path = $path;
        $this->async = $async;
    }

    protected function buildNode()
    {
        $active = new Active_Resolve_Res($this->path);
        $active->setEncodeUrl(false);
        GlobalContext::add($active);
        $this->pathResolver = $active->getResResolver();
        parent::buildNode(); // TODO: Change the autogenerated stub
    }

    public function render()
    {
        $link = new HtmlElement("script");
        $link->setAttribute("src", $resolved = $this->pathResolver->resolve());
        if($this->module)
            $link->setAttribute("type", "module");

        if($this->async)
            $link->setAttribute("async", "true");

        if(is_string($this->module)){

            $importmap = new HtmlElement("script");
            $importmap->setAttribute("type", "importmap");
            $importmap->setContent((new HtmlElementContent('{"imports": {"' . $this->module . '": "' . $resolved . '"}}'))->setRaw());

            return Rendered::of([$importmap, $link]);

        }else{
            return Rendered::of($link);
        }

    }

    /**
     * @param Path $path
     * @param null|string|boolean $module
     */
    public static function addLocal($path, $module=null, $async=false){
        GlobalContext::add(new JSScript($path, $module, $async));
    }
    
    
}