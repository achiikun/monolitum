<?php

namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\frontend\component\AbstractText;
use monolitum\frontend\html\HtmlElement;

class P extends AbstractText
{

    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement("p"), $builder);
    }

    /**
     * @param string|Renderable_Node|callable $content
     * @return P
     */
    public static function of($content)
    {
        if(is_callable($content)){
            $fc = new P($content);
        } else{
            $fc = new P();
            $fc->push($content);
        }
        return $fc;
    }

    /**
     * @param string|Renderable_Node|callable $builder
     * @return P
     */
    public static function add($builder = null)
    {
        $p = self::of($builder);
        GlobalContext::add($p);
        return $p;
    }

}