<?php

namespace monolitum\entity;

use monolitum\core\Active;
use monolitum\core\GlobalContext;
use monolitum\core\Find;
use monolitum\backend\Manager;
use monolitum\core\panic\DevPanic;

class Entities_Manager extends Manager implements Active
{

    /**
     * @var array<class-string, Model>
     */
    private $models = [];

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param class-string $class
     */
    public function getModel($class){
        assert($class != null);

        if($class instanceof Model)
            return $class;

        if(is_string($class)){

            if(array_key_exists($class, $this->models))
                return $this->models[$class];

            /** @var Entity $entity */
            $entity = new $class();
            $model = $entity->buildModel();
            $this->models[$class] = $model;
            return $model;

        }

        throw new DevPanic("Entity model " . get_class($class) . " does not exist");

    }

    /**
     * @param callable $builder
     * @return Entities_Manager
     */
    public static function add($builder)
    {
        $fc = new Entities_Manager($builder);
        GlobalContext::add($fc);
        return $fc;
    }

    /**
     * @param string|Model $entityModel
     * @param bool $forInsert
     * @return Entity
     */
    public function instance($entityModel, $forInsert = false)
    {
        $model = $this->getModel($entityModel);
        $class = $model->getInstancableClass();
        /** @var Entity $inst */
        $inst = new $class();
        $inst->_setModel($model);
        if($forInsert)
            $inst->_setManager($this);
        return $inst;
    }

    /**
     * @param Entity $entity
     * @return void
     */
    public function _notifyEntityChanged($entity)
    {
        /** @var Interface_Entity_DB $face */
        $face = Find::sync(Interface_Entity_DB::class);
        $face->_notifyEntityChanged($entity);
    }

    public function _executeInsertEntity(Entity $entity)
    {
        /** @var Interface_Entity_DB $face */
        $face = Find::sync(Interface_Entity_DB::class);
        return $face->_executeInsertEntity($entity);
    }

    public function _executeUpdateEntity(Entity $entity)
    {
        /** @var Interface_Entity_DB $face */
        $face = Find::sync(Interface_Entity_DB::class);
        return $face->_executeUpdateEntity($entity);
    }

    public function _executeDeleteEntity(Entity $entity)
    {
        /** @var Interface_Entity_DB $face */
        $face = Find::sync(Interface_Entity_DB::class);
        return $face->_executeDeleteEntity($entity);
    }

    /**
     * @param class-string|Model $class
     * @return Model
     */
    public static function go_getModel($class)
    {
        if ($class instanceof Model)
            return $class;
        /** @var Entities_Manager $entities */
        $entities = Find::sync(Entities_Manager::class);
        return $entities->getModel($class);
    }

    /**
     * @param class-string|Model $class
     * @param bool $forInsert
     * @return Entity
     */
    public static function go_instance($class, $forInsert = false){
        /** @var Entities_Manager $entities */
        $entities = Find::sync(Entities_Manager::class);
        return $entities->instance($class, $forInsert);
    }

}