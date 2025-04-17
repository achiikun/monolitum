<?php

namespace monolitum\frontend;

use monolitum\frontend\html\HtmlElement;

interface LinkHook
{
    /**
     * @param Component $component This parameter let the hook grab info about the component. If it is bootstrap or not, etc.
     * @param HtmlElement $element Optional 'a' or 'button' element.
     * @return void
     */
    function buildLinkHook($component, $element = null);

    /**
     * @param Component $component This parameter let the hook grab info about the component. If it is bootstrap or not, etc.
     * @param HtmlElement $element Mandatory 'a' or 'button' element.
     * @return void
     */
    function renderLinkHook($component, $element);

}
