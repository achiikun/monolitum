<?php
namespace monolitum\entity;

use monolitum\entity\attr\Attr;
use monolitum\core\panic\DevPanic;

class Model extends AnonymousModel
{

    /**
     * @var class-string
     */
    private $instancableClass;

    /**
     * @var string|null;
     */
    private $id;

    /**
     * @param string|null $id
     */
    public function __construct($instancableClass, $id = null)
    {
        $this->instancableClass = $instancableClass;
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIdOrClass()
    {
        return $this->id ?: $this->instancableClass;
    }

    /**
     * @return string
     */
    public function getInstanceableClass()
    {
        return $this->instancableClass;
    }

    public function __toString()
    {
        return $this->getId();
    }

}

