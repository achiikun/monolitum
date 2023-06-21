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

/**
 * Html Builder Class
 *
 * @package    HtmlBuilder
 * @author     Sven Sanzenbacher
 */
class HtmlBuilder
{
    /**
     * @access      protected
     * @var         array                   elements require end tag
     */
    protected $_elementsRequireEndTag = array(
        'select','script','i'
    );

    /**
     * @access      protected
     * @var         array                   black list of attributes witch are not filtered
     */
    protected $_nonFilteredAttributes = array('onclick',
        'ondblclick',
        'onmousedown',
        'onmouseup',
        'onmouseover',
        'onmousemove',
        'onmouseout',
        'onkeypress',
        'onkeydown',
        'onkeyup'
    );

    /**
     * @access      protected
     * @param       HtmlElement        $htmlElement
     * @return      string
     */
    protected function renderAttributes(HtmlElement $htmlElement)
    {
        $output = '';
        if($htmlElement->hasClasses()){
            $output .= " class='" . implode(" ", $htmlElement->getClasses()) . "'";
        }
        if($htmlElement->hasStyle()){
            $output .= " style='" . $htmlElement->style()->write() . "'";
        }
        if ($htmlElement->hasAttributes()) {
            $attributes = array();
            $attributeArr = $htmlElement->getAttributes();
            foreach ($attributeArr as $key => $value) {
                if (in_array($key, $this->_nonFilteredAttributes) || $htmlElement->isAttributeNotFiltered($key)) {
                    $attributes[] = $key . '="' . $value . '"';
                } else {
                    $value = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_ENCODE_AMP);
                    $attributes[] = $key . '="' . $value . '"';
                }
            }
            $output .= ' ' . implode(' ', $attributes);
        }
        return $output;
    }

    /**
     * @param       HtmlElement        $htmlElement
     * @return      string                  html output
     */
    public function render(HtmlElement $htmlElement)
    {
        $output = '<' . $htmlElement->getTag();
        $output.= $this->renderAttributes($htmlElement);

        if ($htmlElement->hasChildElements()) {
            $output .= '>';
            $output.= $this->renderContent($htmlElement);
            $output.= '</' . $htmlElement->getTag() . '>';
        } elseif (in_array($htmlElement->getTag(), $this->_elementsRequireEndTag) || $htmlElement->requireEndTag()) {
            $output.= '>';
            $output .= '</' . $htmlElement->getTag() . '>';
        } else {
            $output .= ' />';
        }
        return $output;
    }

    /**
     * @param       HtmlElement        $htmlElement
     * @return      string                  html output
     */
    public function renderContent(HtmlElement $htmlElement)
    {
        $output = '';
        if ($htmlElement->hasChildElements()) {
            foreach ($htmlElement->getChildElementCollection() as $childElement) {
                if ($childElement instanceof HtmlElementContent) {
                    if($childElement->isRaw()){
                        $output.= $childElement->getContent();
                    }else{
                        $output.= filter_var($childElement->getContent(), FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_ENCODE_AMP);
                    }
                } else {
                    $output.= $this->render($childElement);
                }
            }
        }
        return $output;
    }

    /**
     * @param       HtmlElement        $htmlElement
     * @return      string                  html output
     */
    public function renderStartTag(HtmlElement $htmlElement)
    {
        $output = '<' . $htmlElement->getTag();
        $output.= $this->renderAttributes($htmlElement);
        $output .= '>';
        return $output;
    }

    /**
     * @param       HtmlElement        $htmlElement
     * @return      string                  html output
     */
    public function renderEndTag(HtmlElement $htmlElement)
    {
        $output = '</' . $htmlElement->getTag() . '>';
        return $output;
    }
}