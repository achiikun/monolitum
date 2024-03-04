<?php

namespace monolitum\quilleditor;

use monolitum\database\I_Attr_Databasable;
use monolitum\entity\attr\Attr;
use monolitum\entity\ValidatedValue;
use nadar\quill\Lexer;

class Attr_Quill extends Attr implements I_Attr_Databasable
{


    /**
     * @return Attr_Quill
     */
    public static function of(){
        return new Attr_Quill();
    }

    /**
     * @param string $value
     * @return QuillDocument
     */
    private function tryToParseValue($value)
    {
        $lexer = new Lexer($value);

        // We'll check if this method fails
        $rendered = $lexer->render();

        return new QuillDocument($lexer, $rendered);
    }

    public function validate($value)
    {
        if(is_string($value)){

            if(PHP_MAJOR_VERSION >= 7){
                try{
                    $quill = $this->tryToParseValue($value);
                    return new ValidatedValue(true, true, $quill);
                }catch (\Error $exception){
                    print($exception);
                }
            }else{

                try{
                    $quill = $this->tryToParseValue($value);
                    return new ValidatedValue(true, true, $quill);
                }catch (\Exception $exception){
                    // PHP <7 has no Error, catch exception
                }

            }
        }

        return new ValidatedValue(false);
    }

    function getDDLType()
    {
        // Quill is stored in a LONGTEXT type, because might be a large json with embedded images.
        return "LONGTEXT";
    }

    function getInsertUpdatePlaceholder()
    {
        return "?";
    }

    /**
     * @param $rawValue QuillDocument
     * @return string
     */
    function getValueForQuery($rawValue)
    {
        return $rawValue->makeDelta();
    }

    function parseValue($dbValue)
    {
        if($dbValue != null){ // Simple !=

            if(PHP_MAJOR_VERSION >= 7){
                try{
                    $quill = $this->tryToParseValue($dbValue);
                    return $quill;
                }catch (\Error $exception){
                    print($exception);
                }
            }else{

                try{
                    $quill = $this->tryToParseValue($dbValue);
                    return $quill;
                }catch (\Exception $exception){
                    // PHP <7 has no Error, catch exception
                }

            }
        }

        return null;
    }
}