<?php

namespace monolitum\entity;

/**
 * Interface for Nodes to find a parent that can d CRUD over entities.
 */
interface Interface_Entity_DB
{

    /**
     * @param Entity $entity
     * @return int[]
     */
    public function _executeInsertEntity(Entity $entity);

    /**
     * @param Entity $entity
     * @return int[]
     */
    public function _executeUpdateEntity(Entity $entity);

    /**
     * @param Entity $entity
     * @return int[]
     */
    public function _executeDeleteEntity(Entity $entity);

    public function _notifyEntityChanged(Entity $entity);

}