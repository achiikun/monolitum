<?php

namespace monolitum\backend\params;

use monolitum\core\GlobalContext;
use monolitum\core\Find;

class Path
{

    /**
     * @var string[]
     */
    private $path;

    /**
     * @return string[]
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string ...$strings
     * @return Path
     */
    public static function from(...$strings){
        $path = new Path();
        $path->path = $strings;
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
        $path->path = $currentPath;
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
        $path->path = $currentPath;
        return $path;
    }

    public function go_redirect(){
        $active = new Active_SetRedirectPath($this);
        GlobalContext::add($active);
    }


}
