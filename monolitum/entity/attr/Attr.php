<?php
namespace monolitum\entity\attr;

use monolitum\entity\AttrExt;
use monolitum\entity\Model;
use monolitum\entity\ValidatedValue;

abstract class Attr
{

    /**
     * @var string $id
     */
    private $id;

    /**
     * @var Model
     */
    private $model;

    /**
     * @var array<AttrExt>
     */
    private $extensions = [];

    /**
     * @param mixed $extension
     * @return $this
     */
    public function ext($extension)
    {
        $this->extensions[] = $extension;
        return $this;
    }

    /**
     * @param class-string $class
     * @return AttrExt|null
     */
    public function findExtension($class)
    {
        foreach ($this->extensions as $extension) {
            if($extension instanceof $class)
                return $extension;
        }
        return null;
    }

    /**
     * @param string $id
     */
    public function _setModelId($model, $id)
    {
        $this->model = $model;
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
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $value
     * @return ValidatedValue
     */
    public abstract function validate($value);

    public function __toString()
    {
        return $this->getModel() . "->" . $this->getId();
    }

}

