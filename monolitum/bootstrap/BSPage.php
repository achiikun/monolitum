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

        Meta::add("viewport", "width=device-width, initial-scale=1.0");

        CSSLink::addLocal(Path::ofRelativeToClass(BSPage::class,"css","bootstrap-reboot.css"));
        CSSLink::addLocal(Path::ofRelativeToClass(BSPage::class,"css","bootstrap.css"));
        CSSLink::addLocal(Path::ofRelativeToClass(BSPage::class,"css","sorting-table.css"));

        JSScript::addLocal(Path::ofRelativeToClass(BSPage::class,"js","popper.min.js"));
        JSScript::addLocal(Path::ofRelativeToClass(BSPage::class,"js","bootstrap.js"));

    }

    public function includeBootstrapSelect2IfNot()
    {
        $this->includeJQueryIfNot();
        if(!$this->getConstant("bootstrap-select2-js-css")){
            CSSLink::addLocal(Path::ofRelativeToClass(BS::class,"css", "select2.min.css"));
            CSSLink::addLocal(Path::ofRelativeToClass(BS::class,"css", "select2-bootstrap-5-theme.min.css"));
            JSScript::addLocal(Path::ofRelativeToClass(BS::class,"js", "select2.full.min.js"));
            $this->setConstant("bootstrap-select2-js-css");
        }
    }

    public function includeJQueryIfNot()
    {
        if(!$this->getConstant("jquery-js")){
            JSScript::addLocal(Path::ofRelativeToClass(BS::class,"js", "jquery-3.7.1.min.js"));
            $this->setConstant("jquery-js");
        }
    }

    public function includePopperIfNot()
    {
        if(!$this->getConstant("popper-js")){
            JSScript::addLocal(Path::ofRelativeToClass(BS::class,"js", "popper.min.js"));
            $this->setConstant("popper-js");
        }
    }

}