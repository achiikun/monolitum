<?php

namespace monolitum\core\ts;

/**
 * Translatable string
 */
class TS_Default extends TS
{

    private $defaultString = null;
    private $stringsByLanguage = [];

    public function getTranslation($lang, $params = null)
    {
        if($lang === null){
            if($this->defaultString !== null){
                return $this->defaultString;
            }else{
                foreach ($this->stringsByLanguage as $key => $value){
                    return $value;
                }
                return null;
            }
        }else{
            if(array_key_exists($lang, $this->stringsByLanguage)){
                return $this->stringsByLanguage[$lang];
            }else{
                return $this->getTranslation(null);
            }
        }
    }

    public function add($lang, $string)
    {
        if($lang === null){
            $this->defaultString = $string;
        }else{
            $this->stringsByLanguage[$lang] = $string;
        }
    }

    /**
     * @param string[] $string
     * @return TS_Default
     */
    public static function ofStringArray($string){
        $ts = new TS_Default();
        foreach ($string as $lang => $value){
            if($lang === null){
                $ts->defaultString = $string;
            }else{
                $ts->stringsByLanguage[$lang] = $value;
            }
        }
        return $ts;
    }

}