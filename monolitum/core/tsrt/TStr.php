<?php

namespace monolitum\core\tsrt;

/**
 * Translatable string
 */
abstract class TStr
{

    /**
     * @param mixed $string
     * @param string $lang
     * @return string
     */
    public static function unwrap($string, $lang=null)
    {
        if(is_string($string)){
            return $string;
        }else if($string instanceof TStr){
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
     * @return TStr
     */
    public static function of($string){
        return TStr_Default::ofStringArray($string);
    }

}