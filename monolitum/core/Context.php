<?php

namespace monolitum\core;


use monolitum\core\panic\DevPanic;
use monolitum\core\panic\Panic;
use monolitum\core\util\ResourceAddressResolver;

class Context{

    /**
     * @var Panic|null
     */
    private $panic = null;

    /**
     * @var Panic|null
     */
    private $lastPanic = null;

    /**
     * @var Passive[]
     */
    public $passiveStack = [];

    /**
     * @var Passive|null
     */
    private $passive = null;

    /**
     * @var string|null
     */
    private $localAddress = null;

    /**
     * @var string|null
     */
    private $resourcesAddressResolver = null;

    /**
     * @var boolean
     */
    private $debug = false;

    /**
     * @param string $localAddress
     */
    public function setLocalAddress($localAddress){
        $this->localAddress = $localAddress;
    }

    /**
     * @return string
     */
    public function getLocalAddress(){
        return $this->localAddress;
    }

    /**
     * @param ResourceAddressResolver $resourcesAddressResolver
     */
    public function setResourcesAddressResolver($resourcesAddressResolver)
    {
        $this->resourcesAddressResolver = $resourcesAddressResolver;
    }

    /**
     * @return ResourceAddressResolver
     */
    public function getResourcesAddressResolver()
    {
        if($this->resourcesAddressResolver === null)
            $this->resourcesAddressResolver = ResourceAddressResolver::idle();
        return $this->resourcesAddressResolver;
    }

    /**
     * @param Passive $passive
     */
    function pushPassive($passive){
        $this->passiveStack[] = $passive;
        $this->passive = $passive;
    }

    /**
     *
     */
    function popPassive(){
        array_pop($this->passiveStack);
        if(count($this->passiveStack))
            $this->passive = $this->passiveStack[count($this->passiveStack)-1];
        else 
            $this->passive = null;
    }

    /**
     * @param Active $active
     * @param Passive $passive
     */
    public function add($active, $passive = null) {
        assert($active !== null);
        if($this->passive == null){
            throw new DevPanic("Passive stack is empty");
        }else{
            if($passive !== null){
                $passive->_receive($active, 0);
            }else{
                $this->passive->_receive($active, 0);
            }
        }
    }

    /**
     * @param Panic $panic
     */
    function setPanic($panic){
        if($panic != null)
            $this->lastPanic = $panic;
        $this->panic = $panic;
    }

    /**
     * @return Panic|null
     */
    public function getLastPanic(){
        return $this->lastPanic;
    }

    /**
     * @return Panic|null
     */
    public function getPanic(){
        return $this->panic;
    }

    /**
     * @param bool $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->debug;
    }
}