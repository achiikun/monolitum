<?php

namespace backend\params;

use monolitum\backend\Manager;
use monolitum\backend\params\Link;
use monolitum\core\GlobalContext;

class Manager_History extends Manager
{

    /**
     * @var Link
     */
    private $redirectLink;

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    protected function receiveActive($active)
    {


        return parent::receiveActive($active);
    }

    protected function executeNode()
    {

        parent::executeNode();
    }

    /**
     * @param callable|null $builder
     */
    public static function add($builder)
    {
        GlobalContext::add(new Manager_History($builder));
    }

}
