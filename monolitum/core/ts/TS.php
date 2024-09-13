<?php

namespace monolitum\core\ts;

/**
 * Translatable string
 */
abstract class TS
{

    /**
     * @param mixed $string
     * @param string $lang
     * @return string
     */
    public static function unwrapAuto($string){
        return TS::unwrap($string, TSLang::findWithOverwritten());
    }


    /**
     * @param mixed $string
     * @param string $lang
     * @return string
     */
    public static function unwrap($string, $lang=null)
    {
        if(is_string($string)){
            return $string;
        }else if($string instanceof TS){
            return $string->getTranslation($lang);
        }else if(is_array($string)){
            if(array_key_exists($lang, $string)){
                return self::unwrap($string[$lang], $lang);
            }else{
                foreach($string as $firstValue){
                    return self::unwrap($firstValue, $lang);
                }
                return null;
            }
        }else{
            return $string;
        }
    }

    public abstract function getTranslation($lang, $params=null);

    public abstract function add($lang, $string);

    /**
     * @param string[] $string
     * @return TS
     */
    public static function from($string){
        return TS_Default::ofStringArray($string);
    }

}
