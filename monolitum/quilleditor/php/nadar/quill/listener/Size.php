<?php

namespace nadar\quill\listener;

use nadar\quill\InlineListener;
use nadar\quill\Line;

/**
 * Convert Small Inline elements.
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
class Size extends InlineListener
{
    /**
     * {@inheritDoc}
     */
    public function process(Line $line)
    {
        if ($size = $line->getAttribute('size')) {
            if($size == "small"){
                $this->updateInput($line, '<small>'.$line->getInput().'</small>');
            }else if($size == "large"){
                $this->updateInput($line, '<big>'.$line->getInput().'</big>');
            }else if($size == "huge"){
                $this->updateInput($line, '<big><big>'.$line->getInput().'</big></big>');
            }
        }
    }
}
