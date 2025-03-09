<?php

namespace monolitum\backend\params;

use monolitum\core\GlobalContext;
use monolitum\core\Find;

class Path
{

    /**
     * @var string[]
     */
    private $strings;

    /**
     * @return string[]
     */
    public function getStrings()
    {
        return $this->strings;
    }

    /**
     * @param string ...$strings
     * @return Path
     */
    public static function from(...$strings){
        $path = new Path();
        $path->strings = $strings;
        return $path;
    }

    /**
     * @param int $back
     * @param string ...$strings
     * @return Path
     */
    public static function ofRelative($back = 0, ...$strings){

        /** @var Manager_Path $m */
        $m = Find::sync(Manager_Path::class);
        $currentPath = $m->getCurrentPathCopy();

        $currentPathSize = sizeof($currentPath);

        if($back > $currentPathSize)
            $back = $currentPathSize;

        array_splice(
                $currentPath,
                $currentPathSize-$back,
                $back,
                $strings
        );

        $path = new Path();
        $path->strings = $currentPath;
        return $path;
    }

    /**
     * @param class-string $class
     * @param string ...$strings
     * @return Path
     */
    public static function ofRelativeToClass($class, ...$strings)
    {
        $currentPath = explode('\\', $class);

        array_splice(
            $currentPath,
            count($currentPath)-1,
            1,
            $strings
        );

        $path = new Path();
        $path->strings = $currentPath;
        return $path;
    }

    public function go_redirect(){
        $active = new Active_SetRedirectPath($this);
        GlobalContext::add($active);
    }


    /**
     * @param bool $encodeUrl;
     * @param string $separator;
     * @return string|null
     */
    public function writePath($encodeUrl=true, $separator="/")
    {

        if($this->strings){
            $path = "";
            $first = true;
            foreach ($this->strings as $string) {
                if($first){
                    $first = false;
                }else{
                    $path .= $separator;
                }
                $path .= $encodeUrl ? urlencode($string) : $string;
            }
            return $path;
        }else{
            return null;
        }
    }

    /**
     * @param string $url
     * @return Path
     */
    public static function fromUrl($url, $pathSeparator="/")
    {

        if(strlen($url) > 0){
            $newPath = [];
            foreach (explode($pathSeparator, $url) as $value){
                if(strlen($value) > 0){
                    $newPath[] = $value;
                }
            }

            if(count($newPath) > 0){
                return Path::from(...$newPath);
            }
        }

        return Path::from();
    }


}
