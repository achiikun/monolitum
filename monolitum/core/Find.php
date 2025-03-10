<?php

namespace monolitum\core;

use monolitum\core\panic\DevPanic;

class Find implements Active
{

    public $class;
    private $callable;

    private $throwIfNotReceived = true;

    /**
     * @var Passive|null
     */
    private $responded = null;

    /**
     * @var bool
     */
    private $cache;

    /**
     * @param class-string $class
     * @param callable $callable
     */
    function __construct($class, $callable, $cache)
    {
        $this->class = $class;
        $this->callable = $callable;
        $this->cache = $cache;
    }

    public function dontThrowIfNotReceived()
    {
        $this->throwIfNotReceived = false;
    }

    /**
     * @param Context $context
     * @param Active|Passive $router
     * @return void
     */
    public function respond($context, $router)
    {
        $this->responded = $router;
        // Not necessary to push, it is not building
//        try{
//            $context->pushPassive($router);
            $c = $this->callable;
            $c($router);
//        } finally {
//            $context->popPassive();
//        }
    }

    public function onNotReceived()
    {
        if($this->throwIfNotReceived){
            throw new DevPanic("Active $this->class not found.");
        }
    }

    /**
     * @param class-string $class
     * @param bool $cache
     * @return Active|Passive|null
     */
    static function sync($class, $cache=true, $dontThrowIfNotReceived=false){
        $var = new Ref();
        $find = new Find($class, function($r) use ($var) {
            $var->value = $r;
        }, $cache);
        if($dontThrowIfNotReceived)
            $find->dontThrowIfNotReceived();
        GlobalContext::add($find);
        return $var->value;
    }

    /**
     * @param class-string $class
     * @param Passive $passive
     * @param bool $cache
     * @return Active|Passive|null
     */
    static function syncFrom($class, $passive, $cache=true, $dontThrowIfNotReceived=false){
        $var = new Ref();
        $find = new Find($class, function($r) use ($var) {
            $var->value = $r;
        }, $cache);
        if($dontThrowIfNotReceived)
            $find->dontThrowIfNotReceived();
        GlobalContext::add($find, $passive);
        return $var->value;
    }

    public function wantsToCache()
    {
        return $this->cache;
    }

    /**
     * @return Passive|null
     */
    public function getResponded()
    {
        return $this->responded;
    }


}

