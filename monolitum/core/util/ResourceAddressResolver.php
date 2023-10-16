<?php

namespace monolitum\core\util;

class ResourceAddressResolver
{

    /**
     * @var array<callable>
     */
    private $prefixes = [];

    /**
     * When encountering an url that starts with $prefix, append $additionalPrefix to it.
     * (No matter slashes)
     * @param string $prefix
     * @param string $additionalPrefix
     * @return void
     */
    public function prefix($prefix, $additionalPrefix){
        $this->prefixes[$prefix] = function ($url) use ($additionalPrefix) {
            return $additionalPrefix . $url;
        };
    }

    /**
     * @param $url
     * @return string
     */
    public function resolve($url){
        foreach ($this->prefixes as $prefix => $callable){
            if(substr($url, 0, strlen($prefix)) === $prefix){
                return $callable($url);
            }
        }
        return $url;
    }

    public static function idle()
    {
        $rar = new ResourceAddressResolver();
        return $rar;
    }

    public static function fromPrefix($prefix, $additionalPrefix)
    {
        $rar = new ResourceAddressResolver();
        $rar->prefix($prefix, $additionalPrefix);
        return $rar;
    }

}