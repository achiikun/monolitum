<?php

namespace monolitum\backend\res;

use monolitum\core\GlobalContext;
use monolitum\backend\Manager;
use monolitum\backend\params\Active_Path2UrlPath;
use monolitum\backend\params\Active_Make_Url;
use monolitum\backend\params\Link;
use monolitum\backend\params\Path;

class Manager_Res_Resolver extends Manager
{

    /**
     * @var Path
     */
    private $writePath;

    /**
     * @var string
     */
    private $writeResourceParam;

    /**
     * @param callable|null $builder
     */
    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param Path $writePath
     */
    public function setWritePath($writePath)
    {
        $this->writePath = $writePath;
    }

    /**
     * @param string $writeResourceParam
     */
    public function setWriteResourceParam($writeResourceParam)
    {
        $this->writeResourceParam = $writeResourceParam;
    }

    /**
     * @param ResResolver_Impl $param
     * @return string
     */
    public function makeRes($param)
    {
        // p = res
        // r = path

        $active = new Active_Path2UrlPath($param->getPath(), $param->isEncodeUrl());
        GlobalContext::add($active);

        $link = new Link($this->writePath);
        $link->addParams([
            $this->writeResourceParam => $active->getUrl()
        ]);

        $active = new Active_Make_Url($link);
        GlobalContext::add($active);

        return $active->getUrl();
    }

    protected function receiveActive($active)
    {
        if($active instanceof Active_Resolve_Res){
            $active->setResResolver(new ResResolver_Impl($this, $active->getPath(), $active->isEncodeUrl()));
            return true;
        }

        return parent::receiveActive($active);
    }

    /**
     * @param callable|null $builder
     */
    public static function add($builder)
    {
        GlobalContext::add(new Manager_Res_Resolver($builder));
    }

}