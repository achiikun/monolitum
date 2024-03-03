<?php

namespace monolitum\quilleditor;

use monolitum\database\I_Attr_Databasable;
use monolitum\entity\attr\Attr;
use monolitum\entity\ValidatedValue;
use monolitum\monolitum\quilleditor\QuillDocument;
use nadar\quill\Lexer;

class Attr_Quill extends Attr implements I_Attr_Databasable
{


    /**
     * @return Attr_Quill
     */
    public static function of(){
        return new Attr_Quill();
    }

    public function validate($value)
    {
        if(is_string($value)){
            try{
                $lexer = new Lexer($value);

                // We'll check if this method fails
                $rendered = $lexer->render();

                $quill = new QuillDocument($lexer, $rendered);

                return new ValidatedValue(true, true, $quill);

            }catch (\Exception $exception){

            }
        }

        return new ValidatedValue(false);
    }

    function getDDLType()
    {
        // Quill is stored in a LONGTEXT type, because might be a large json with embedded images.
        return "LONGTEXT";
    }
}