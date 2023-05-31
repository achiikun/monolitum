<?php

namespace monolitum\auth;

use monolitum\core\Active;
use monolitum\core\GlobalContext;
use monolitum\backend\Manager;

class Auth_Role_Manager extends Manager implements Active
{

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param callable $builder
     * @return Auth_Role_Manager
     */
    public static function add($builder)
    {
        $fc = new Auth_Role_Manager($builder);
        GlobalContext::add($fc);
        return $fc;
    }

    function onNotReceived()
    {
        // TODO: Implement onNotReceived() method.
    }
}