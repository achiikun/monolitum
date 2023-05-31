<?php
namespace monolitum\entity;

use monolitum\entity\attr\Attr;
use monolitum\core\panic\DevPanic;

class Model
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
     * @var array<string, Attr>
     */
    private $attrs = [];

    /**
     * @param string|null $id
     */
    public function __construct($instancableClass, $id = null)
    {
        $this->instancableClass = $instancableClass;
        $this->id = $id;
    }

    /**
     * @param string $attrId
     * @param Attr $attr
     */
    public function attr($attrId, $attr)
    {
        $attr->_setModelId($this, $attrId);
        if(key_exists($attrId, $this->attrs))
            throw new DevPanic("Id $attrId already exists in " . $this->__toString());
        $this->attrs[$attrId] = $attr;
    }

    /**
     * @param string|Attr $attrId
     * @return Attr
     */
    public function getAttr($attrId)
    {
        if($attrId instanceof Attr)
            return $attrId;
        if(key_exists($attrId, $this->attrs))
            return $this->attrs[$attrId];

        throw new DevPanic("Attr $attrId of Model $this not found.");
    }

    /**
     * @return array<Attr>
     */
    public function getAttrs()
    {
        return array_values($this->attrs);
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
    public function getInstancableClass()
    {
        return $this->instancableClass;
    }

    public function __toString()
    {
        return $this->getId();
    }

    /**
     * @param Attr|string $attr
     * @return bool
     */
    public function hasAttr($attr)
    {
        if($attr instanceof Attr)
            return in_array($attr, $this->attrs);

        foreach ($this->attrs as $attr2){
            if($attr2->getId() == $attr)
                return true;
        }

        return false;
    }
}

