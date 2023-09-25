<?php

namespace monolitum\core;

use monolitum\backend\router\Router_Panic;
use monolitum\core\panic\DevPanic;
use monolitum\core\panic\Panic;

abstract class Node implements Passive {

    /**
     * @var Context
     */
    private $ctx;

    /**
     * @var Node|null
     */
    private $parent = null;

    /**
     * @var Node|null
     */
    private $panicRouter = null;

    /**
     * @var callable|null
     */
    private $builder;

    /**
     * @var bool
     */
    private $building = false;

    /**
     * @var bool
     */
    private $built = false;

    /**
     * @var bool
     */
    private $panicked = false;

    /**
     * @var array<class-string, Passive>
     */
    private $passiveCacheByClassName = [];

    public $experimental_letBuildChildsAfterBuild = false;

    /**
     * @param callable|null $builder
     */
    function __construct($builder = null){
        $this->builder = $builder;
    }

    /**
     * @return bool
     */
    public function isBuilding()
    {
        return $this->building;
    }

    /**
     * @return bool
     */
    public function isBuilt()
    {
        return $this->built;
    }

    /**
     * @param Node|null $panicRouter
     */
    public function setPanicRouter($panicRouter)
    {
        $this->panicRouter = $panicRouter;
    }

    /**
     * @param Active $actives
     * @return void
     */
    public function push(...$actives){
        foreach ($actives as $active) {
            $this->_receive($active, 0);
        }
    }
    
    final function _receive($active, $currentDepth) {

        $processed = false;

        if($active instanceof Find){
            if($this instanceof $active->class){
                $active->respond($this->ctx, $this);
                return; // not cache
            }else{
                // find in cache
                if(
                    isset($this->passiveCacheByClassName[$active->class])
                    || array_key_exists($active->class, $this->passiveCacheByClassName)
                ){
                    $active->respond($this->ctx, $this->passiveCacheByClassName[$active->class]);
                    return; // not (re)cache
                }
            }
        }else if($active instanceof Router_Panic){
            $this->setPanicRouter($active);
            $processed = true;
        }else{
            $processed = $this->receiveActive($active);
        }

        if(!$processed) {
            if ($this->parent != null) {
                $this->parent->_receive($active, $currentDepth+1);
            }else{
                $active->onNotReceived();
            }
        }else{
            if($currentDepth === 0 && $active instanceof Find && $active->wantsToCache()) {
                $response = $active->getResponded();
                if($response != null){
                    $this->passiveCacheByClassName[$active->class] = $response;
                }
            }
        }

        
    }

    /**
     * @param Context $ctx
     * @param Node|null $parent
     */
    final function _build($ctx, $parent){
        if($this->built)
            return;

        assert(!($this->building));

        $this->ctx = $ctx;
        $this->parent = $parent;

        $this->building = true;

        $ctx->pushPassive($this);

        try{
            $this->buildNode();
            $this->afterBuildNode();
            $this->built = true;
        }catch (Panic $panic){
            $ctx->setPanic($panic);
            if($this->panicRouter != null){
                $this->panicRouter->_build($ctx, $this);
                $this->panicked = true;
            }else{
                throw $panic;
            }
        }finally{
            $ctx->popPassive();
        }

    }
    
    final function _execute(){

        assert($this->built || $this->panicked);

        $this->ctx->pushPassive($this);

        if($this->panicked){
            assert($this->panicRouter != null);
            $this->panicRouter->_execute();
        }else{
            $this->executeNode();
        }

        $this->ctx->popPassive();
        
    }

    /**
     * @return Context
     */
    public function getContext(){
        return $this->ctx;
    }

    /**
     * @return Node
     */
    public function getParent(){
        return $this->parent;
    }

    /**
     * Requests a child of this node to be built.
     * @param Node $child
     * @return Node
     */
    public function buildChild($child){
        if(!$this->experimental_letBuildChildsAfterBuild && $this->built)
            throw new DevPanic("No adding childs after build");
        if($child instanceof Node)
            $child->_build($this->ctx, $this);
        return $child;
    }

    /**
     * @param Node $child
     * @return Node
     */
    public function executeChild($child){
        if($child instanceof Node)
            $child->_execute();
        return $child;
    }

    /**
     * This method builds, configures, panics
     */
    protected function buildNode(){
        $this->runLambdaNode();
    }

    /**
     * This method is called after build, to prepare before executing.
     * It may panic if the previous build was illegal.
     * @deprecated
     */
    protected function afterBuildNode(){

    }

    /**
     * @param Active $active
     * @return bool
     *  true if active received successfully.
     *  false if it must be forwarded into parents.
     */
    protected function receiveActive($active)
    {

//        echo "<p>ola ";
//        print_r(get_class($active));
//        echo "<p>";
//        foreach (GlobalContext::getGlobalContext()->passiveStack as $item) {
//            print_r(get_class($item));
//            echo " ";
//        }

        return false;
    }

    /**
     * This method cannot panic.
     */
    protected function executeNode(){
        
    }

    public function __toString()
    {
        return get_class($this) . "()";
    }

    /**
     * @return void
     */
    public function runLambdaNode()
    {
        if ($this->builder != null) {
            $b = $this->builder;
            $b($this);
        }
    }

}