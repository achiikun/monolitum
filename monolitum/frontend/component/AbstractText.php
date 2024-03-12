<?php

namespace monolitum\frontend\component;

use monolitum\core\ts\TS;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElementContent;

abstract class AbstractText extends ElementComponent
{

    public function __construct($element, $builder = null)
    {
        parent::__construct($element, $builder);
    }

    /**
     * @param string|TS $text
     */
    public function appendText($text)
    {
        $this->append(new HtmlElementContent(TS::unwrap($text)));
    }

}