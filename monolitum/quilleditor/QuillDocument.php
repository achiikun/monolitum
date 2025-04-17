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
        if($this->rendered === null){
            $this->rendered = $this->lexer->render();
        }
        return $this->rendered;
    }

    /**
     * @param string $search
     * @param string $replace
     * @return void
     */
    public function replace($search, $replace)
    {
        $json = $this->lexer->getJsonArray();

        foreach ($json as &$jsonValue) {
            if(isset($jsonValue["insert"])){
                $insert = $jsonValue["insert"];
                if(is_string($insert)){
                    $insert = str_replace($search, "$replace", $insert);
                    $jsonValue["insert"] = $insert;
                }
            }
        }

        $this->lexer = new Lexer($json);
        $this->rendered = $this->lexer->render();//str_replace($search, "$replace", $this->rendered);
    }

}
