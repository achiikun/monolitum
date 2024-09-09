<?php
namespace monolitum\entity;

use DateTime;
use monolitum\entity\attr\Attr;
use monolitum\core\panic\DevPanic;

abstract class Entity
{

    /**
     * @var Model
     */
    private $model;

    /**
     * @var array<string, mixed>
     */
    private $values = [];

    /**
     * @var bool
     */
    private $protectWrite = false;

    /**
     * @var Entities_Manager|null
     */
    private $manager = null;

    /**
     * This array is not null if Entities_Manager wants to keep track of changes in attributes.
     * @var array|null
     */
    private $updateAttrs = null;

    /**
     * @var bool
     */
    protected $hasBeenNotified;

    /**
     * @param Model $model
     * @return void
     */
    public function _setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    public function getAttr($attr)
    {
        if(is_string($attr))
            $attr = $this->model->getAttr($attr);
        return $attr;
    }

    /**
     * @param Attr|string $attr
     * @param mixed $value
     * @return $this
     */
    private function _set($attr, $value)
    {
        if($this->protectWrite)
            throw new DevPanic("Entity is not writable.");
        if($attr instanceof Attr)
            $attr = $attr->getId();
        $this->values[$attr] = $value;
        if ($this->updateAttrs !== null) {
            $this->updateAttrs[$attr] = $value;
        }
        if (!$this->hasBeenNotified && $this->manager != null) {
            $this->manager->_notifyEntityChanged($this);
            $this->hasBeenNotified = true;
        }
        return $this;
    }

    /**
     * If manager is set, then it is notified if the entity is updated
     * @param Entities_Manager $entityManager
     * @return void
     */
    public function _setManager($entityManager)
    {
        $this->manager = $entityManager;
        $this->updateAttrs = [];
    }

    /**
     * @param string $attr
     * @return string
     */
    public function getString($attr) {
        if($attr instanceof Attr)
            $attr = $attr->getId();
        return key_exists($attr, $this->values) ? $this->values[$attr] : null;
    }

    /**
     * @param Attr|string $attr
     * @param string $string
     * @return $this
     */
    public function setString($attr, $string){
        return $this->_set($attr, $string);
    }

    /**
     * @param string $attr
     * @return int
     */
    public function getInt($attr) {
        if($attr instanceof Attr)
            $attr = $attr->getId();
        return key_exists($attr, $this->values) ? $this->values[$attr] : null;
    }

    /**
     * @param Attr|string $attr
     * @param int $int
     * @return $this
     */
    public function setInt($attr, $int){
        return $this->_set($attr, $int);
    }

    /**
     * @param string $attr
     * @return bool
     */
    public function getBool($attr) {
        if($attr instanceof Attr)
            $attr = $attr->getId();
        return key_exists($attr, $this->values) ? $this->values[$attr] : null;
    }

    /**
     * @param Attr|string $attr
     * @param bool $bool
     * @return $this
     */
    public function setBool($attr, $bool){
        return $this->_set($attr, $bool);
    }

    /**
     * @param string $attr
     * @return DateTime
     */
    public function getDate($attr) {
        if($attr instanceof Attr)
            $attr = $attr->getId();
        return key_exists($attr, $this->values) ? $this->values[$attr] : null;
    }

    /**
     * @param Attr|string $attr
     * @param DateTime $date
     * @return $this
     */
    public function setDate($attr, $date){
        return $this->_set($attr, $date);
    }

    /**
     * @param string $attr
     * @return object
     */
    public function getValue($attr)
    {
        if($attr instanceof Attr)
            $attr = $attr->getId();
        return key_exists($attr, $this->values) ? $this->values[$attr] : null;
    }


    /**
     * @param Attr|string $attr
     * @param $value
     * @return $this
     */
    public function setValue($attr, $value)
    {
        return $this->_set($attr, $value);
    }

    abstract function buildModel();

    public function _protectWrite()
    {
        $this->protectWrite = true;
    }

    public function hasValue($attr)
    {
        if($attr instanceof Attr)
            return key_exists($attr->getId(), $this->values);
        return key_exists($attr, $this->values);

    }

    /**
     * @return array|null
     */
    public function getUpdateAttrs()
    {
        return $this->updateAttrs;
    }

    public function update()
    {
        $this->manager->_executeUpdateEntity($this);
    }

    /**
     * @return int|false
     */
    public function insert()
    {
        $returned = $this->manager->_executeInsertEntity($this);
        return $returned[1];
    }

    public function delete()
    {
        $this->manager->_executeDeleteEntity($this);
    }

}

