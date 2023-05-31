<?php

namespace monolitum\backend\res;

use monolitum\backend\params\Path;

class AllowedExtension
{
    /**
     * @var Manager_Res_Provider
     */
    private $manager;

    /**
     * @var string
     */
    private $mimeType = null;

    /**
     * @param Manager_Res_Provider $manager
     * @return void
     */
    public function prepare($manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return Manager_Res_Provider
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return string|null
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function readLineByLine()
    {
        return false;
    }

    /**
     * @param Path $path
     * @return callable|null
     */
    public function getRewriter(&$path)
    {
        return null;
    }

    /**
     * @param string $mimeType
     * @return AllowedExtension
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

}