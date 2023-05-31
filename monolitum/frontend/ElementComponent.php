<?php
namespace monolitum\frontend;

use monolitum\frontend\css\Style;
use monolitum\frontend\html\HtmlElement;

class ElementComponent extends Component
{
    /**
     * @var HtmlElement
     */
    private $element;

    /**
     * @var array<ElementComponent_Ext>
     */
    private $extensions = [];

    /**
     * @param HtmlElement $element
     * @param callable|null $builder
     */
    public function __construct($element, $builder = null)
    {
        parent::__construct($builder);
        $this->element = $element;
    }

    /**
     * set attribute to html element
     *
     * @param       string      $key    html element attribute key
     * @param       string      $value  html element attribute value
     * @return      $this
     */
    public function setAttribute($key, $value = null){
        $this->element->setAttribute($key, $value);
        return $this;
    }

    /**
     * @param       string      $id         html element attribute id
     * @return      $this
     */
    public function setId($id)
    {
        $this->setAttribute('id', $id);
        return $this;
    }

    /**
     * @param string $classes
     * @return $this
     */
    public function addClass(...$classes) {
        $this->element->addClass(...$classes);
        return $this;
    }

    /**
     * @return Style
     */
    public function style() {
        return $this->element->style();
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content){
        $this->element->setContent($content);
        return $this;
    }

    /**
     * @return HtmlElement
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->element->getAttribute("id");
    }

    protected function receiveActive($active)
    {
        if($active instanceof ElementComponent_Ext){
            $active->_setElementComponent($this);
            $active->_build($this->getContext(), $this);
            $this->extensions[] = $active;
            return true;
        }

        return parent::receiveActive($active);
    }

    protected function afterBuildNode(){

        foreach ($this->extensions as $extension) {
            $extension->apply();
        }

    }

    public function render()
    {

        $rc = parent::render();
        $rc->renderTo($this->element);

        return Rendered::of($this->element);
    }

}

