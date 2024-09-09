<?php

namespace monolitum\quilleditor;

use nadar\quill\Lexer;

class QuillDocument
{

    /**
     * @var Lexer
     */
    private $lexer;

    /**
     * @var string
     */
    private $rendered;

    function __construct($lexer, $rendered)
    {
        $this->lexer = $lexer;
        $this->rendered = $rendered;
    }

    /**
     * @return string
     */
    public function makeDelta()
    {
        return json_encode($this->lexer->getJsonArray());
    }

    /**
     * @return string
     */
    public function renderHTML()
    {
        return $this->rendered;
    }

    /**
     * @param string $search
     * @param string $replace
     * @return void
     */
    public function replace($search, $replace)
    {
        $this->rendered = str_replace($search, "$replace", $this->rendered);
    }

}
