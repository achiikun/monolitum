<?php
namespace monolitum\entity;

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
        $id = $this->getId();
        if(!is_null($id))
            return $id;
        return  parent::__toString();
    }

}

