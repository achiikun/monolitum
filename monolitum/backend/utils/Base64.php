<?php

namespace monolitum\backend\utils;

class Base64
{

    /**
     * @param string $rawData
     * @param bool $forUrl
     * @return string
     */
    public static function encodeBase64($rawData, $forUrl=false)
    {
        if($rawData === null){
            return null;
        }
        if($forUrl){
            return rtrim( strtr( base64_encode( $rawData ), '+/', '-_'), '=');
        }else{
            return base64_encode( $rawData );
        }

    }

    /**
     * @param string $encodedData
     * @return false|string
     */
    public static function decodeBase64($encodedData)
    {
        if($encodedData === null){
            return null;
        }
        return base64_decode( strtr($encodedData , '-_', '+/'));
    }


}