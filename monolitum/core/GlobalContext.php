<?php

namespace monolitum\core;


use monolitum\core\panic\Panic;

class GlobalContext{

    /**
     * @var Context|null
     */
    private static $globalContext = null;

    /**
     * @param Context|null $context
     */
    public static function setGlobalContext($context){
        self::$globalContext = $context;
    }

    /**
     * @return Context
     */
    public static function getGlobalContext(){
        return self::$globalContext;
    }

    /**
     * @return string
     */
    public static function getLocalAddress(){
        return self::$globalContext->getLocalAddress();
    }

    /**
     * @return string
     */
    public static function getResourcesAddress(){
        return self::$globalContext->getResourcesAddress();
    }

    /**
     * @param Active $active
     * @param Passive $passive
     * @return Active
     */
    public static function add($active, $passive = null) {
        self::$globalContext->add($active, $passive);
        return $active;
    }

    /**
     * @param Panic $panic
     */
    public static function setPanic($panic){
        self::$globalContext->setPanic($panic);
    }

    /**
     * @return Panic|null
     */
    public static function getLastPanic() {
        return self::$globalContext->getLastPanic();
    }

    /**
     * @return Panic|null
     */
    public static function getPanic(){
        return self::$globalContext->getPanic();
    }
    
    
}