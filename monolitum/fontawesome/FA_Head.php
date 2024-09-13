<?php

namespace monolitum\fontawesome;

use monolitum\core\GlobalContext;
use monolitum\core\Node;
use monolitum\frontend\component\CSSLink;
use monolitum\frontend\component\Head;
use monolitum\backend\params\Path;

class FA_Head extends Node implements Head
{

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    protected function buildNode()
    {
        CSSLink::addLocal(Path::from("monolitum", "fontawesome", "res", "css", "all.css"));
        CSSLink::addLocal(Path::from("monolitum", "fontawesome", "res", "css", "fontawesome.css"));
        parent::buildNode();
    }

    /**
     * @return FA_Head
     */
    public static function add(){
        $h = new FA_Head();
        GlobalContext::add($h);
        return $h;
    }


    function onNotReceived()
    {
        // TODO: Implement onNotReceived() method.
    }
}
