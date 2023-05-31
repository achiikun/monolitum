<?php

namespace monolitum\backend\params;

use monolitum\core\Active;

class Active_Path_BuildParent implements Active
{

    /**
     * @var int
     */
    private $parents;

    /**
     * @var Path
     */
    private $path;

    public function __construct($parents)
   {
       $this->parents = $parents;
   }

    /**
     * @return int
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * @return Path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param Path $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    function onNotReceived()
    {
        // TODO: Implement onNotReceived() method.
    }
}