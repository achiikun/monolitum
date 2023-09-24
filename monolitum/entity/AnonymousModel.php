<?php
namespace monolitum\entity;

use monolitum\entity\attr\Attr;
use monolitum\core\panic\DevPanic;

class AnonymousModel
{

    /**
     * @var array<string, Attr>
     */
    private $attrs = [];

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
     * @throws DevPanic if attribute not found in model
     * @param string|Attr $attrId
     * @return Attr
     */
    public function getAttr($attrId)
    {
        if($attrId instanceof Attr){
            if(!key_exists($attrId->getId(), $this->attrs))
                throw new DevPanic("Attr $attrId of Model $this not found.");
            return $attrId;
        }

        if(!key_exists($attrId, $this->attrs))
            throw new DevPanic("Attr $attrId of Model $this not found.");

        return $this->attrs[$attrId];
    }

    /**
     * @return array<Attr>
     */
    public function getAttrs()
    {
        return array_values($this->attrs);
    }

    public function __toString()
    {
        return "Anonymous Model";
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

