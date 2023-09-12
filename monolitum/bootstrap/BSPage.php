<?php

namespace monolitum\bootstrap;

use monolitum\frontend\component\CSSLink;
use monolitum\frontend\component\JSScript;
use monolitum\frontend\component\Meta;
use monolitum\frontend\HTMLPage;
use monolitum\backend\params\Path;

class BSPage extends HTMLPage{
    
    public function buildPage()
    {
        parent::buildPage();

        Meta::push("viewport", "width=device-width, initial-scale=1.0");

        CSSLink::addLocal(Path::ofRelativeToClass(BSPage::class,"css","bootstrap-reboot.css"));
        CSSLink::addLocal(Path::ofRelativeToClass(BSPage::class,"css","bootstrap.css"));
        CSSLink::addLocal(Path::ofRelativeToClass(BSPage::class,"css","sorting-table.css"));

        JSScript::addLocal(Path::ofRelativeToClass(BSPage::class,"js","popper.min.js"));
        JSScript::addLocal(Path::ofRelativeToClass(BSPage::class,"js","bootstrap.js"));

    }
    
    
}