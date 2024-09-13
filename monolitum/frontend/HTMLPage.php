<?php

namespace monolitum\frontend;

use monolitum\core\Node;
use monolitum\core\Renderable;
use monolitum\core\Renderable_Node;
use monolitum\frontend\component\Body;
use monolitum\frontend\component\Head;
use monolitum\frontend\html\HtmlBuilder;
use monolitum\frontend\html\HtmlElement;

class HTMLPage extends Component {

    const HTML_VERSION_KEY = "html_version";
    const HTML_VERSION_VALUE_4 = "html";
    const HTML_VERSION_VALUE_5 = "html5";


    private $pageConstants = [];

    /**
     * @var array<Head>
     */
    private $head_components = [];

//    /**
//     * @var array<Body>
//     */
//    private $body_components = [];

    /**
     * @var ElementComponent
     */
    private $body;

    /**
     * @param callable|null $builder
     */
    function __construct($builder = null){
        $this->body = new ElementComponent(new HtmlElement('body'));
        parent::__construct($builder);
    }

    /**
     * @param Head $head_component
     */
    public function addHeadElement($head_component){
        if($head_component instanceof Node)
            $this->head_components[] = $this->buildChild($head_component);
    }

    /**
     * @param Renderable_Node $body_component
     */
    public function addBodyElement($body_component){
        $this->body->push($body_component);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getConstant($key, $defaultValue=null){
        return key_exists($key, $this->pageConstants) ? $this->pageConstants[$key] : $defaultValue;
    }

    /**
     * TODO: Create constant manager
     * @param string $key
     * @param $value
     * @return void
     */
    public function setConstant($key, $value=true){
        $this->pageConstants[$key] = $value;
    }

    protected function receiveActive($active)
    {
        if($active instanceof Head){
            // TODO detect CSS and remove duplicates
            // TODO detect JS? and remove duplicates
            $this->addHeadElement($active);
            return true;
        }else if($active instanceof Renderable_Node){
            $this->addBodyElement($active);
            return true;
        }else if($active instanceof ElementComponent_Ext){
            $this->body->push($active);
            return true;
        }
        return parent::receiveActive($active);
    }

    public function buildComponent()
    {
        $this->buildPage();

//        if($this->getContext()->getPanic())
//            return;

        $this->buildChild($this->body);

    }

    public function executeComponent()
    {

        $html = new HtmlElement('html');

        $head = new HtmlElement('head');
        foreach($this->head_components as $head_component){
            if($head_component instanceof Node)
                $this->executeChild($head_component);

            Renderable_Node::renderRenderedTo($head_component, $head);

        }
        $html->addChildElement($head);

//        parent::executeComponent();

        $this->executeChild($this->body);

//        Renderable_Node::renderRenderedTo(parent::render(), $body);

//        foreach($this->body_components as $body_component){
//            if($body_component instanceof Node)
//                $this->executeChild($body_component);
//            Renderable_Node::renderRenderedTo($body_component, $body);
//        }

        Renderable_Node::renderRenderedTo($this->body->render(), $html);

        $htmlBuilder = new HtmlBuilder();
        echo $htmlBuilder->render($html);

    }

    function buildPage(){

    }

}
