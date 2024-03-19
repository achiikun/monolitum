<?php

namespace monolitum\backend\crypto;

use Closure;

class AsymmetricKey
{

    /**
     * @var string|Closure
     */
    private $publicKey;

    /**
     * @var string|Closure|null
     */
    private $privateKey;

    /**
     * @var string
     */
    private $hashAlgorithm;

    private $config = null;


    /**
     * @param Manager_Crypto $manager
     * @return Closure|string|null
     */
    public function getPrivateKey($manager)
    {
        if($this->privateKey !== null){
            if(is_callable($this->privateKey)){
                $callable = $this->privateKey;
                $this->privateKey = $callable($manager);
            }
        }
        return $this->privateKey;
    }

    /**
     * @param Manager_Crypto $manager
     * @return Closure|string|null
     */
    public function getPublicKey($manager)
    {
        if($this->publicKey !== null){
            if(is_callable($this->publicKey)){
                $callable = $this->publicKey;
                $this->publicKey = $callable($manager);
            }
        }
        return $this->publicKey;
    }

    /**
     * @return null
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     *
     * @param string|Callable $publicKey PEM
     * @param string|Callable|null $privateKey
     * @return AsymmetricKey
     */
    public static function of($publicKey, $privateKey=null, $config=null)
    {
        $self = new AsymmetricKey();
        $self->publicKey = $publicKey;
        $self->privateKey = $privateKey;
        $self->config = $config;
        return $self;
    }

}