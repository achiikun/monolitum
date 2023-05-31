<?php

namespace monolitum\fontawesome;

use monolitum\core\GlobalContext;
use monolitum\frontend\component\CSSLink;
use monolitum\frontend\component\Head;
use monolitum\frontend\Rendered;
use monolitum\backend\params\Path;

class FA_Head extends Head
{

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    protected function buildNode()
    {
        CSSLink::addLocal(Path::of("monolitum", "fontawesome", "res", "css", "all.css"));
        CSSLink::addLocal(Path::of("monolitum", "fontawesome", "res", "css", "fontawesome.css"));
        parent::buildNode();
    }

    public function render()
    {
        return Rendered::ofEmpty();
    }

    /**
     * @return FA_Head
     */
    public static function add(){
        $h = new FA_Head();
        GlobalContext::add($h);
        return $h;
    }


}