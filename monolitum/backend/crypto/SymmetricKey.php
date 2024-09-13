<?php

namespace monolitum\backend\crypto;

use Closure;
use monolitum\core\Node;

class SymmetricKey
{

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $algorithm = null;

    /**
     * @var string
     */
    private $defaultInitializationVector = null;

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * @return string|null
     */
    public function getDefaultInitializationVector()
    {
        return $this->defaultInitializationVector;
    }

    /**
     *
     * @param $password
     * @param $defaultInitializationVector
     * @return SymmetricKey
     */
    public static function from($password, $algorithm=null, $defaultInitializationVector=null)
    {
        $self = new SymmetricKey();
        $self->password = $password;
        $self->algorithm = $algorithm;
        $self->defaultInitializationVector = $defaultInitializationVector;
        return $self;
    }

}
