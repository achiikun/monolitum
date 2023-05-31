<?php

namespace monolitum\backend\globals;

use monolitum\core\Active;
use monolitum\core\GlobalContext;
use monolitum\core\panic\DevPanic;

class Active_NewId implements Active
{

    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    function onNotReceived()
    {
        throw new DevPanic();
    }

    /**
     * @return string
     */
    public static function go_newId(){
        $active = new Active_NewId();
        GlobalContext::add($active);
        return $active->getId();
    }

}