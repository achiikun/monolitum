<?php

namespace monolitum\backend\res;

use monolitum\backend\params\Path;

class ResResolver_Impl implements ResResolver
{

    /**
     * @var Path
     */
    private $path;

    /**
     * @var Manager_Res_Resolver
     */
    private $manager;

    /**
     * @var bool
     */
    private $encodeUrl;

    /**
     * @param Manager_Res_Resolver $manager
     * @param Path $link
     */
    public function __construct($manager, $link, $encodeUrl)
    {
        $this->path = $link;
        $this->manager = $manager;
        $this->encodeUrl = $encodeUrl;
    }

    /**
     * @return Path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return bool
     */
    public function isEncodeUrl()
    {
        return $this->encodeUrl;
    }

    function resolve()
    {
        return $this->manager->makeRes($this);
    }
}