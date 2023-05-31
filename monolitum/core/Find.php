<?php

namespace monolitum\core;

use monolitum\core\panic\DevPanic;

class Find implements Active
{

    public $class;
    private $callable;

    private $throwIfNotReceived = true;

    /**
     * @param class-string $class
     * @param callable $callable
     */
    function __construct($class, $callable)
    {
        $this->class = $class;
        $this->callable = $callable;
    }

    public function dontThrowIfNotReceived()
    {
        $this->throwIfNotReceived = false;
    }

    public function respond(Context $context, Passive $router)
    {
        try{
            $context->pushPassive($router);
            $c = $this->callable;
            $c($router);
        } finally {
            $context->popPassive();
        }
    }

    public function onNotReceived()
    {
        if($this->throwIfNotReceived){
            throw new DevPanic("Active $this->class not found.");
        }
    }

    /**
     * @param class-string $class
     * @return Passive|null
     */
    static function sync($class){
        $var = new Ref();
        GlobalContext::add(new Find($class, function($r) use ($var) {
            $var->value = $r;
        }));
        return $var->value;
    }

    /**
     * @param class-string $class
     * @param Passive $passive
     * @return Passive|null
     */
    static function syncFrom($class, $passive){
        $var = new Ref();
        $passive->_receive(new Find($class, function($r) use ($var) {
            $var->value = $r;
        }));
        return $var->value;
    }

}

