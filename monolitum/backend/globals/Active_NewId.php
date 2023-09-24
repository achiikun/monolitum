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
    private $contextIds;

    /**
     * @var string
     */
    private $id;

    /**
     * @param string $contextIds
     */
    public function __construct($contextIds)
    {
        $this->contextIds = $contextIds;
    }

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

    /**
     * @return string
     */
    public function getContextIds()
    {
        return $this->contextIds;
    }

    function onNotReceived()
    {
        throw new DevPanic();
    }

    /**
     * @return string
     */
    public static function go_newId($contextIds=null){
        $active = new Active_NewId($contextIds);
        GlobalContext::add($active);
        return $active->getId();
    }

}