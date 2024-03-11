<?php

namespace monolitum\core\tsrt;

use monolitum\core\Active;
use monolitum\core\Find;
use monolitum\core\Passive;

class TStrLang implements Active
{
    public $lang;

    /**
     * @param $lang
     */
    public function __construct($lang)
    {
        $this->lang = $lang;
    }

    public static function findWithOverwritten($overwritten=null)
    {
        if($overwritten !== null){
            return $overwritten;
        }else{
            /** @var TStrLang $tstrlang */
            $tstrlang = Find::sync(TStrLang::class, true, true);
            if($tstrlang !== null){
                return $tstrlang->lang;
            }else{
                return null;
            }
        }
    }

    /**
     * @param string $lang
     * @return TStrLang
     */
    public static function of($lang)
    {
        return new TStrLang($lang);
    }

    function onNotReceived()
    {
        // TODO: Implement onNotReceived() method.
    }
}