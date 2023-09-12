<?php

namespace monolitum\database;

use monolitum\core\Find;
use monolitum\entity\attr\Attr;
use monolitum\entity\attr\Attr_Bool;
use monolitum\entity\attr\Attr_Date;
use monolitum\entity\attr\Attr_Decimal;
use monolitum\entity\attr\Attr_Int;
use monolitum\entity\attr\Attr_String;
use monolitum\entity\Entities_Manager;
use monolitum\entity\Entity;
use monolitum\entity\Model;
use PDO;
use PDOStatement;

class Query_Result
{

    /**
     * @var Entities_Manager
     */
    private $manager;

    /**
     * @var Entities_Manager
     */
    private $entityManager;


    /**
     * @var PDOStatement
     */
    private $stmt;

    /**
     * @var array<Attr>
     */
    private $select;

    /**
     * @var bool
     */
    private $protectForUpdate;

    /**
     * @var Model
     */
    private $model;

    /**
     * @var Entity|null
     */
    private $nextRow;
    private $iteratorKey = 0;

    /**
     * @var bool
     */
    private $finished = false;

    /**
     * @param Manager_DB $manager
     * @param Model $model
     * @param array<Attr> $select
     * @param bool $protectForUpdate
     * @param PDOStatement $stmt
     */
    public function __construct($manager, $model, $select, $protectForUpdate, $stmt)
    {

        $this->manager = $manager;
        $this->entityManager = Find::syncFrom(Entities_Manager::class, $manager);
        $this->model = $model;
        $this->select = $select;
        $this->protectForUpdate = $protectForUpdate;
        $this->stmt = $stmt;
    }

    public function hasNext()
    {
        if($this->finished)
            return false;
        if($this->nextRow != null)
            return true;
        $this->nextRow = $this->next();
        return $this->nextRow !== null;
    }

    /**
     * @return Entity|null
     */
    public function next()
    {
        if($this->finished)
            return null;

        if($this->nextRow != null){
            $ret = $this->nextRow;
            $this->nextRow = null;
            return $ret;
        }

        $row = $this->stmt->fetch(PDO::FETCH_ASSOC);
        if($row === false){
            $this->close();
            return null;
        }

        $entity = $this->entityManager->instance($this->model);

        foreach ($this->select as $attr){

            $rowValue = $row[$attr->getId()];

            if($rowValue !== null){

                if($attr instanceof Attr_String){
                    $entity->setString($attr, strval($rowValue));
                }else if($attr instanceof Attr_Int){
                    $entity->setInt($attr, intval($rowValue));
                }else if($attr instanceof Attr_Decimal){
                    $entity->setInt($attr, intval($rowValue));
                }else if($attr instanceof Attr_Bool){
                    if(is_int($rowValue))
                        $rowValue = $rowValue != 0;
                    else if($rowValue === "true")
                        $rowValue = true;
                    else if($rowValue === "false")
                        $rowValue = false;
                    $entity->setBool($attr, $rowValue);
                }else if($attr instanceof Attr_Date){
                    $entity->setDate($attr, date_create($rowValue));
                }

            }

        }

        if($this->protectForUpdate){
            $entity->_setManager($this->entityManager);
        }else{
            $entity->_protectWrite();
        }

        if(is_null($this->iteratorKey))
            $this->iteratorKey = 0;
        else
            $this->iteratorKey++;

        return $entity;

    }

    public function first()
    {
        $entity = $this->next();
        $this->close();
        return $entity;
    }

    /**
     *
     */
    public function close()
    {
        $this->finished = true;
        $this->stmt->closeCursor();
    }

    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->nextRow;
    }

    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->iteratorKey;
    }

}