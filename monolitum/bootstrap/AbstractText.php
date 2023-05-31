<?php

namespace monolitum\bootstrap;

use monolitum\bootstrap\values\BSWeight;
use monolitum\frontend\html\HtmlElementContent;

abstract class AbstractText extends BSElementComponent
{

    /**
     * @var int|null
     */
    private $sizeNum = null;

    /**
     * @var BSWeight|null
     */
    private $weight;

    /**
     * @var bool|null
     */
    private $italic = null;

    public function __construct($element, $builder = null)
    {
        parent::__construct($element, $builder);
    }

    /**
     * @param string $text
     */
    public function appendText($text)
    {
        $this->append(new HtmlElementContent($text));
    }

    public function size($sizeNum)
    {
        $this->sizeNum = $sizeNum;
    }

    /**
     * @param BSWeight $weight
     * @return void
     */
    public function weight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @param bool $italic
     * @return void
     */
    public function italic($italic = true)
    {
        $this->italic = $italic;
    }

    protected function afterBuildNode()
    {

        if($this->sizeNum != null)
            $this->addClass("fs-" . $this->sizeNum);

        if($this->weight != null)
            $this->addClass("fw-" . $this->weight->getValue());

        if($this->italic !== null){
            if($this->italic)
                $this->addClass("fst-italic");
            else
                $this->addClass("fst-normal");
        }

        parent::afterBuildNode();
    }

}