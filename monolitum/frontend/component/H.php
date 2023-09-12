<?php

namespace monolitum\frontend\component;

use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\frontend\html\HtmlElement;

class H extends AbstractText
{

    public function __construct($level = 1, $builder = null)
    {
        parent::__construct(new HtmlElement("H" . $level), $builder);
    }

    /**
     * @param int $level
     * @param string|Renderable_Node $content
     * @return H
     */
    public static function of($level, $content)
    {
        $fc = new H($level);
        $fc->push($content);
        return $fc;
    }

    /**
     * @param callable $builder
     * @return H
     */
    public static function build($level, $builder = null)
    {
        return new H($level, $builder);
    }

    /**
     * @param $level
     * @param callable $builder
     * @return H
     */
    public static function add($level, $builder = null)
    {
        $fc = new H($level, $builder);
        GlobalContext::add($fc);
        return $fc;
    }

}