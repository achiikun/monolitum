<?php

namespace monolitum\frontend;

use monolitum\core\Renderable_Node;
use monolitum\frontend\component\Body;
use monolitum\frontend\component\Head;
use monolitum\frontend\html\HtmlBuilder;
use monolitum\frontend\html\HtmlElement;

class HTMLPage extends Component {

    private $pageConstants = [];

    /**
     * @var array<Head>
     */
    private $head_components = [];

    /**
     * @var array<Body>
     */
    private $body_components = [];

    /**
     * @param callable|null $builder
     */
    function __construct($builder = null){
        parent::__construct($builder);
    }

    /**
     * @param Head $head_component
     */
    public function addHeadElement($head_component){
        $this->head_components[] = $this->buildChild($head_component);
    }

    /**
     * @param Renderable_Node $body_component
     */
    public function addBodyElement($body_component){
        $this->body_components[] = $this->buildChild($body_component);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getConstant($key){
        return key_exists($key, $this->pageConstants) ? $this->pageConstants[$key] : null;
    }

    /**
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
        }
        return parent::receiveActive($active);
    }
    
    public function buildComponent()
    {
        
        $this->buildPage();
        if($this->getContext()->getPanic())
            return;

    }
    
    public function executeComponent()
    {

        $html = new HtmlElement('html');
        
        $head = new HtmlElement('head');
        foreach($this->head_components as $head_component){
            $this->executeChild($head_component);
            $rendered = $head_component->render();
            if($rendered !== null)
                $rendered->renderTo($head);
        }
        $html->addChildElement($head);
        
        $body = new HtmlElement('body');
        parent::executeComponent();

        $rendered = parent::render();
        if($rendered !== null)
            $rendered->renderTo($body);

        foreach($this->body_components as $body_component){
            $this->executeChild($body_component);
            $rendered = $body_component->render();
            if($rendered !== null)
                $rendered->renderTo($body);
        }
        $html->addChildElement($body);
        
        $htmlBuilder = new HtmlBuilder();
        echo $htmlBuilder->render($html);
        
    }
    
    function buildPage(){
        
    }
    
    
}