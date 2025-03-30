<?php

namespace monolitum\core\util;

class ResourceAddressResolver
{

    private $strictMode = true;

    /**
     * @var array<callable>
     */
    private $prefixes = [];

    /**
     * When encountering an url that starts with $prefix, append $additionalPrefix to it.
     * (No matter slashes)
     * @param string $prefix
     * @param string $additionalPrefix
     * @return ResourceAddressResolver
     */
    public function prefix($prefix, $additionalPrefix){
        $this->prefixes[$prefix] = function ($url) use ($additionalPrefix) {
            return $additionalPrefix . $url;
        };
        return $this;
    }

    public function nonStrictMode($nonStrictMode = true)
    {
        $this->strictMode = !$nonStrictMode;
        return $this;
    }

    /**
     * @param $url
     * @return string|null
     */
    public function resolve($url){
        // Split url into parts and instafail if it has illegal terms
        $split_res = preg_split("/\//", $url, -1);
        foreach ($split_res as $part) {
            if($part === '.' || $part === '..' || trim($part) === '' || substr($part, 0, 1) === '$') {
                return null;
            }
        }
        foreach ($this->prefixes as $prefix => $callable){
            if(substr($url, 0, strlen($prefix)) === $prefix){
                return $callable($url);
            }
        }
        return $this->strictMode ? null : $url;
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
