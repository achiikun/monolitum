<?php
/*
 * Copyright 2008 Sven Sanzenbacher
 *
 * This file is part of the naucon package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace monolitum\frontend\html;

use monolitum\core\Renderable;
use monolitum\frontend\css\Style;

/**
 * Html Element Class
 *
 * @package    HtmlBuilder
 * @author     Sven Sanzenbacher
 */
class HtmlElement implements Renderable
{

    /**
     * Constructor
     *
     * @param       string      $tag        html element tag
     * @param       string      $content    optional html element content
     */
    public function __construct($tag, $content = null)
    {
        $this->tag = $tag;
        $this->setContent($content);
    }

    /**
     * @access      protected
     * @var         string                  html element tag
     */
    protected $tag = null;

    /**
     * @access      protected
     * @var         array                     html element attributes with key-value-pairs
     */
    protected $attributeMap = null;

    /**
     *
     * @var array<string>
     */
    private $classes = [];

    /**
     * @var Style|null
     */
    private $style = null;

//    private $isStyleDirty = false;

    /**
     * @access      protected
     * @var         array              child element collection
     */
    protected $childElementCollection = null;

    protected $requireEndTag = false;

    /**
     * @access      protected
     * @var         array                   black list of attributes witch are not filtered
     */
    protected $nonFilteredAttributes = [];

    /**
     * @return      string                  html element tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string|null $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return      string                  html element attribute id
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @param       string      $id         html element attribute id
     * @return      HtmlElement
     */
    public function setId($id)
    {
        $this->setAttribute('id', $id);
        return $this;
    }

    /**
     * @access      protected
     * @return      array
     */
    protected function getAttributeMap()
    {
        if (is_null($this->attributeMap)) {
            $this->attributeMap = [];
        }
        return $this->attributeMap;
    }

    /**
     * @return      array                   html element attributes with key-value-pairs
     */
    public function getAttributes()
    {
        return $this->getAttributeMap();
    }

    /**
     * @return      bool                    html element has attributes
     */
    public function hasAttributes()
    {
        if ($this->attributeMap !== null && count($this->attributeMap) > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param       array   $attributes     html element attributes with key-value-pairs
     * @return      HtmlElement
     */
    public function setAttributes(array $attributes)
    {
        $this->attributeMap = $attributes;
        return $this;
    }

    /**
     * @param       string      $key        html element attribute key
     * @return      string                  html element attribute value
     */
    public function getAttribute($key)
    {
        if ($this->attributeMap != null && array_key_exists($key, $this->attributeMap)){
            return $this->attributeMap[$key];
        }
        return null;
    }

    /**
     * @param       string      $key        html element attribute key
     * @return      bool                    html element has attribute for given key
     */
    public function hasAttribute($key)
    {
        return $this->attributeMap != null && array_key_exists($key, $this->attributeMap);
    }

    /**
     * set attribute to html element
     *
     * @param       string      $key        html element attribute key
     * @param       string      $value      html element attribute value
     * @return      HtmlElement
     */
    public function setAttribute($key, $value = null, $filter = true)
    {
        if (is_null($value)) {
            if($this->attributeMap !== null){
                if(array_key_exists($key, $this->attributeMap)){
                    unset($this->attributeMap[$key]);
                }
                if (in_array($key, $this->nonFilteredAttributes) !== false) {
                    unset($this->nonFilteredAttributes[$key]);
                }
            }
        } else {
            if($this->attributeMap === null)
                $this->attributeMap = [];
            $this->attributeMap[$key] = $value;
            if($filter){
                if (in_array($key, $this->nonFilteredAttributes) !== false) {
                    unset($this->nonFilteredAttributes[$key]);
                }
            }else{
                if (!in_array($key, $this->nonFilteredAttributes)) {
                    $this->nonFilteredAttributes[] = $key;
                }
            }
        }
        return $this;
    }

    /**
     * remove attribute of html element
     *
     * @param       string      $key        html element attribute key
     * @return      HtmlElement
     */
    public function removeAttribute($key)
    {
        if (!is_null($this->attributeMap)) {
            unset($this->attributeMap[$key]);
        }
        return $this;
    }

    /**
     * @param string $classes
     * @return $this
     */
    public function addClass(...$classes) {
        foreach ($classes as $class)
            $this->classes[] = $class;
        return $this;
    }

    public function hasClasses(){
        return count($this->classes) > 0;
    }

    public function getClasses(){
        return $this->classes;
    }

    public function hasStyle(){
        return $this->style !== null;
    }

    /**
     * @return Style
     */
    public function style() {
        if($this->style === null)
            $this->style = new Style();
        return $this->style;
    }

    /**
     * @return      array
     */
    public function getChildElementCollection()
    {
        if (is_null($this->childElementCollection)) {
            $this->childElementCollection = [];
        }
        return $this->childElementCollection;
    }

    /**
     * @return      array                   html child elements
     */
    public function getChildElements()
    {
        $this->getChildElementCollection();
    }

    /**
     * @return      bool                    true = html element has child elements
     */
    public function hasChildElements()
    {
        if (count($this->getChildElementCollection()) > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param       array       $elements   html child elements
     * @return      HtmlElement
     */
    public function setChildElements(array $elements)
    {
        $this->childElementCollection = $elements;
        return $this;
    }

    /**
     * add html element as child element
     *
     * @param       HtmlElement|HtmlElementContent        $element
     * @return      HtmlElement
     */
    public function addChildElement($element)
    {
        $this->childElementCollection[] = $element;
        return $this;
    }

    /**
     * set html element content, replace all child elements
     *
     * @param       string|HtmlElement|HtmlElementContent     $content        html element content
     * @return      HtmlElement
     */
    public function setContent($content)
    {
        if (!is_null($content)) {
            if ($content instanceof HtmlElement || $content instanceof HtmlElementContent) {
                $htmlElementObject = $content;
            }else{
                $htmlElementObject = new HtmlElementContent($content);
            }
            $this->childElementCollection = [$htmlElementObject];
        }
        return $this;
    }

    /**
     * add html element content
     *
     * @param       string      $content        html element content
     * @return      HtmlElement
     */
    public function addContent($content = null)
    {
        if (!is_null($content)) {
            $htmlElementObject = new HtmlElementContent($content);
            $this->childElementCollection[] = $htmlElementObject;
        }
        return $this;
    }

    /**
     * @param bool $requireEndTag
     * @return $this
     */
    public function setRequireEndTag($requireEndTag)
    {
        $this->requireEndTag = $requireEndTag;
        return $this;
    }

    public function requireEndTag()
    {
        return $this->requireEndTag;
    }

    public function isAttributeNotFiltered($key)
    {
        return in_array($key, $this->nonFilteredAttributes);
    }

    /**
     * @return      string                  html output
     */
    public function __toString()
    {
        $htmlBuilder = new HtmlBuilder();
        return $htmlBuilder->render($this);
    }

    function renderTo($element)
    {
        if($element instanceof HtmlElement)
            $element->addChildElement($this);
    }
}