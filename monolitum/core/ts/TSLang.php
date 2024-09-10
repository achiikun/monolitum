<?php

namespace monolitum\core\ts;

use monolitum\core\Active;
use monolitum\core\Find;

class TSLang implements Active
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
            return self::find();
        }
    }

    public static function find()
    {
        /** @var TSLang $tstrlang */
        $tstrlang = Find::sync(TSLang::class, true, true);
        if($tstrlang !== null){
            return $tstrlang->lang;
        }else{
            return null;
        }
    }

    /**
     * @param string $lang
     * @return TSLang
     */
    public static function of($lang)
    {
        return new TSLang($lang);
    }

    function onNotReceived()
    {
        // TODO: Implement onNotReceived() method.
    }
}
