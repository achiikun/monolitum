<?php

namespace monolitum\database;

use DateTime;
use monolitum\backend\Manager;
use monolitum\core\Active;
use monolitum\core\Find;
use monolitum\core\GlobalContext;
use monolitum\core\panic\DevPanic;
use monolitum\entity\attr\Attr;
use monolitum\entity\attr\Attr_Bool;
use monolitum\entity\attr\Attr_Date;
use monolitum\entity\attr\Attr_Decimal;
use monolitum\entity\attr\Attr_Int;
use monolitum\entity\attr\Attr_String;
use monolitum\entity\AttrExt_Validate_String;
use monolitum\entity\Entities_Manager;
use monolitum\entity\Entity;
use monolitum\entity\Interface_Entity_DB;
use monolitum\entity\Model;
use PDO;

class Manager_DB extends Manager implements Active, Interface_Entity_DB
{

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var string
     */
    private $prefix = "";

    /**
     * @var Entities_Manager
     */
    private $entitiesManager;

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param PDO $pdo
     */
    public function setPdo($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    protected function buildNode()
    {
        $this->entitiesManager = Find::sync(Entities_Manager::class);
        parent::buildNode(); // TODO: Change the autogenerated stub
    }


    /**
     * @param Model|class-string $entityModel
     * @return Insert
     */
    public function newInsert($entityModel)
    {
        return new Insert($this, $this->entitiesManager->getModel($entityModel));
    }

    /**
     * @param Model $entityModel
     * @return Update
     */
    public function newUpdate($entityModel)
    {
        return new Update($this, $this->entitiesManager->getModel($entityModel));
    }

    /**
     * @param Model $entityModel
     * @return Delete
     */
    public function newDelete($entityModel)
    {
        return new Delete($this, $this->entitiesManager->getModel($entityModel));
    }

    /**
     * @param Model $entityModel
     * @return Query_Entities_Executor
     */
    public function newQuery($entityModel)
    {
        return new Query_Entities_Executor($this, $this->entitiesManager->getModel($entityModel));
    }

    /**
     * @param class-string|Model $entity
     * @param string $attr
     * @param string $operation
     * @return Query_Aggregation_Executor
     */
    public function newQuery_Aggregation($entity, $attr, $operation)
    {
        $model = $this->entitiesManager->getModel($entity);
        return new Query_Aggregation_Executor($this, $model, $model->getAttr($attr), $operation);
    }

    /**
     * @return Join
     */
    public function newJoin($entity, $attrs)
    {
        $entityModel = $this->entitiesManager->getModel($entity);

        $attrs2 = [];
        foreach ($attrs as $attr) {
            $attrs2[] = $entityModel->getAttr($attr);
        }

        return new Join($this, $entityModel, $attrs2);
    }

    /**
     * @param Entity $entity
     * @param string $attr
     * @return void
     */
    public function _notifyChanged($entity, $attr)
    {

    }

    /**
     * @param string[] $array
     * @return string
     */
    public function generateDB($array)
    {

        $sql = "";

        foreach ($array as $modelClass){

            $model = Entities_Manager::go_getModel($modelClass);

            $id = $model->getId();
            if($id == "")
                throw new DevPanic("Id is null");

            /** @var Attr[] $ids */
            $ids = [];
            $autoIncrement = null;

            $sql .= "CREATE TABLE " . $this->prefix . $id . "(\n";

            foreach ($model->getAttrs() as $attr) {

                /** @var AttrExt_DB $dbExt */
                $dbExt = $attr->findExtension(AttrExt_DB::class);
                if($dbExt == null)
                    continue;

                $isId = $dbExt->isId();
                if($isId && $autoIncrement != null)
                    throw new DevPanic("Autoincrement models cannot have more than one id");

                $isAutoincrement = $dbExt->isAutoincrement();
                if($isAutoincrement && count($ids) > 0)
                    throw new DevPanic("Autoincrement models cannot have more than one id");
                if($isAutoincrement && !($attr instanceof Attr_Int))
                    throw new DevPanic("Autoincrement must be of type integer");

                if($isAutoincrement){
                    $autoIncrement = $attr;
                }else if($isId){
                    $ids[] = $attr;
                }

                $sql .= "\t" . $attr->getId();

                if($attr instanceof Attr_Int){
                    $sql .= " INT";
                    if($isAutoincrement)
                        $sql .= " AUTO_INCREMENT";
                }else if($attr instanceof Attr_Decimal){
                    $sql .= " INT";
                }else if($attr instanceof Attr_String){
                    /** @var AttrExt_Validate_String $validateString */
                    $validateString = $attr->findExtension(AttrExt_Validate_String::class);
                    $limit = null;
                    if($validateString != null){
                        $limit = $validateString->getMaxChars();
                    }

                    if($limit == null || $limit < 65535/4){
                        $sql .= " TEXT";
                    }else if($limit < 16777215/4){
                        $sql .= " MEDIUMTEXT";
                    }else{
                        $sql .= " LONGTEXT";
                    }

                }else if($attr instanceof Attr_Bool){

                    $sql .= " TINYINT(1)";

                }else if($attr instanceof Attr_Date){
                    $sql .= " DATE";
                }else {
                    throw new DevPanic("Not recognized type");
                }

                $sql .= ",\n";

            }

            $sql .= "\tPRIMARY KEY(";

            $first = true;
            if($autoIncrement != null){
                $first = false;
                $sql .= $autoIncrement->getId();
            }
            foreach($ids as $id){
                if($first){
                    $first = false;
                }else{
                    $sql .= ", ";
                }
                $sql .= $id->getId();
            }
            $sql .= ")\n";

            $default_charset = 'utf8mb4';
            $default_collation = 'utf8mb4_general_ci';

            $sql .= ") CHARSET " . $default_charset
                . " COLLATE " . $default_collation
                . " ENGINE MyISAM;\n";

        }

        return $sql;
    }

    /**
     * @param Query_Entities $query
     * @param Model $model
     * @param array<Attr> $selectAttrs
     * @return string
     */
    public function execute_generate_select($query, $model, &$selectAttrs)
    {
        $sql = "SELECT ";

        $selectAttrs = $query->getSelectAttrs();
        $first = true;
        if ($selectAttrs == null) {
            foreach ($model->getAttrs() as $attr) {
                $ext = $attr->findExtension(AttrExt_DB::class);
                if ($ext != null) {
                    if ($first)
                        $first = false;
                    else
                        $sql .= ", ";
                    $sql .= '`' . $attr->getId() . '`';
                    $selectAttrs[] = $attr;
                }
            }
        } else {
            foreach ($selectAttrs as $attrId) {
                $attr = $model->getAttr($attrId);
                $ext = $attr->findExtension(AttrExt_DB::class);
                if ($ext != null) {
                    if ($first)
                        $first = false;
                    else
                        $sql .= ", ";
                    $sql .= $attr->getId();
                    $selectAttrs[] = $attr;
                }
            }
        }

        return $sql;
    }

    /**
     * @param Insert|Update|Delete $query
     * @return array<int|bool>
     */
    public function executeUpdate($query)
    {
        $model = $query->getModel();

        $values = [];
        if($query instanceof Update){
            $sql = $this->executeUpdate_Update($query, $model, $values);
        }else if($query instanceof Insert){
            $sql = $this->executeUpdate_Insert($query, $model, $values);
        }else if($query instanceof Delete){
            $sql = $this->executeUpdate_Delete($query, $model, $values);
        }else{
            throw new DevPanic("Query type not supported");
        }

        error_log($sql);

        $stmt = $this->pdo->prepare($sql);

        foreach ($values as $idx => $value){
            if(is_null($value)){
                $stmt->bindValue($idx+1, $value, PDO::PARAM_NULL);
            }else if(is_bool($value)){
                $stmt->bindValue($idx+1, $value, PDO::PARAM_BOOL);
            }else if(is_int($value)){
                $stmt->bindValue($idx+1, $value, PDO::PARAM_INT);
            }else if($value instanceof DateTime){
                $stmt->bindValue($idx+1, date_format($value, DateTime::ATOM), PDO::PARAM_STR);
            }else{
                $stmt->bindValue($idx+1, $value);
            }
        }

        $stmt->execute();

        $lastInsert = $this->pdo->lastInsertId();

        $count = $stmt->rowCount();

        $stmt->closeCursor();

        return [$count, $lastInsert !== false ? intval($lastInsert) : false];
    }

    /**
     * @param Insert $query
     * @param Model $model
     * @param array $values
     * @return string
     */
    private function executeUpdate_Insert($query, $model, &$values){
        $sql = "INSERT INTO " . $this->prefix . $model->getId() . "(";

        $count = 0;
        foreach ($query->getValues() as $attrName => $value) {
            if($count > 0)
                $sql .= ",";
            $sql .= "`" . $attrName . "`";
            $values[] = $value;
            $count++;
        }

        $sql .= ") VALUES (";

        for($i = 0; $i < $count; $i++){
            if($i === 0)
                $sql .= "?";
            else
                $sql .= ",?";
        }

        $sql .= ")";

        return $sql;
    }

    /**
     * @param Update $query
     * @param Model $model
     * @param array $values
     * @return string
     */
    private function executeUpdate_Update($query, $model, &$values)
    {
        $sql = "UPDATE " . $this->prefix . $model->getId() . " SET ";

        $count = 0;
        foreach ($query->getValues() as $attrName => $value) {
            if($count > 0)
                $sql .= ",";
            $sql .= "`" . $attrName . "` = ?";
            $values[] = $value;
            $count++;
        }

        if($count == 0)
            throw new DevPanic("Update of 0 values");

        $sql .= $this->execute_generate_where($query->getFilter(), $model, $values);

        return $sql;
    }

    /**
     * @param Delete $query
     * @param Model $model
     * @param array $values
     * @return string
     */
    private function executeUpdate_Delete($query, $model, &$values)
    {
        $sql = "DELETE FROM " . $this->prefix . $model->getId();

        $sql .= $this->execute_generate_where($query->getFilter(), $model, $values);

        return $sql;
    }

    /**
     * @param Query $query
     * @return Query_Result
     */
    public function executeQuery($query)
    {

        $model = $query->getModel();

        $selectAttrs = [];
        if($query instanceof Query_Entities_Executor){
            $sql = $this->execute_generate_select($query, $model, $selectAttrs);
        }else if($query instanceof Query_Aggregation_Executor){
            $sql = "SELECT " . $query->getOperation() . "(`" . $query->getSelectAttr()->getId() . "`)" ;
        }else{
            throw new DevPanic();
        }

        $sql .= " FROM " . $this->prefix . $model->getId();

        $values = [];
        $sql .= $this->execute_generate_where($query->getFilter(), $model, $values);

        $sortedAttrs = $query->getSortedAttrs();
        if($sortedAttrs){
            $sql .= " ORDER BY ";
            $sortedAttrsAsc = $query->getSortedAttrsAsc();

            $i = 0;
            foreach ($sortedAttrs as $sortedAttr){
                if($i > 0)
                    $sql .= ",";
                $sql .= " " . $sortedAttr . " " . ($sortedAttrsAsc[$i] ? "ASC " : "DESC ");
                $i++;
            }

        }

        $low = $query->getLimitLow();
        $high = $query->getLimitMany();

        if($low !== null && $high !== null){
            $sql .= " LIMIT ?, ?";
            $values[] = $low;
            $values[] = $high;
        }

        if($query instanceof Query_Entities_Executor){
            if($query->isForUpdate())
                $sql .= " FOR UPDATE";
        }

        error_log($sql);

        $stmt = $this->pdo->prepare($sql);

        foreach ($values as $idx => $value){
            if(is_null($value)){
                $stmt->bindValue($idx+1, $value, PDO::PARAM_NULL);
            }else if(is_bool($value)){
                $stmt->bindValue($idx+1, $value, PDO::PARAM_BOOL);
            }else if(is_int($value)){
                $stmt->bindValue($idx+1, $value, PDO::PARAM_INT);
            }else{
                $stmt->bindValue($idx+1, $value);
            }
        }

        $stmt->execute();

        if($query instanceof Query_Entities_Executor){
            return new Query_Result($this, $model, $selectAttrs, $query->isForUpdate(), $stmt);
        }else if($query instanceof Query_Aggregation_Executor){
            $result = $stmt->fetch(PDO::FETCH_NUM)[0];
            $stmt->closeCursor();
            return $result;
        }

    }


    /**
     * @param mixed $filter
     * @param Model $model
     * @param array $values
     * @return string
     */
    public function execute_generate_where($filter, $model, &$values)
    {

        $sql = "";

        if($filter != null){

            $sql .= $this->execute_generate_where_filter($filter, $model, $values);

            if($sql == ""){
                return $sql;
            }else{
                return " WHERE " . $sql;
            }

        }

        return $sql;
    }

    public function execute_generate_where_filter($filter, $model, &$values)
    {

        $sql = "";

        if(is_array($filter)){
            // parse and
            $sql .= $this->execute_generate_where_list($filter, $model, $values);
        }else if($filter instanceof Query_Or){

            $or = $this->execute_generate_where_list($filter->getFilters(), $model, $values, "OR");

            if($or !== ""){
                $sql .= "($or)";
            }
        }

        return $sql;
    }

    public function execute_generate_where_list($filters, $model, &$values, $operation = "AND")
    {

        $sql = "";

        $first = true;
        foreach ($filters as $attrId => $filter){
            if(is_string($attrId) && $attrId !== ""){
                $attr = $model->getAttr($attrId);
                $ext = $attr->findExtension(AttrExt_DB::class);
                if ($ext != null) {
                    if ($first)
                        $first = false;
                    else
                        $sql .= " $operation ";

                    $sql .= $this->execute_generate_where_attr($attr, $filter, $model, $values);

                }
            }else {
                $sql2 = $this->execute_generate_where_filter($filter, $model, $values);

                if($sql2 !== ""){
                    if ($first)
                        $first = false;
                    else
                        $sql .= " $operation ";
                    $sql .= $sql2;
                }
            }
        }

        return $sql;
    }

    /**
     * @param callable $builder
     * @return Manager_DB
     */
    public static function add($builder)
    {
        $fc = new Manager_DB($builder);
        GlobalContext::add($fc);
        return $fc;
    }

    private function execute_generate_where_attr($attr, $filter, $model, &$values)
    {

        $sql = $attr->getId();

        if($filter === null){
            $sql .= " IS NULL ";
        }else if($filter instanceof Query_NotNull){
            $sql .= " IS NOT NULL ";
        }else if($filter instanceof Query_Like){

            $string = $filter->getString();

            $processedString = "";

            $pos = 0;
            foreach ($filter->getParams() as $param){

                $param = str_replace("\'", "\\\\'", $param);
                $param = str_replace("!", "!!", $param);
                $param = str_replace("%", "!%", $param);
                $param = str_replace("_", "!_", $param);

                $nextExclamation = strpos($string, "?", $pos);

                if($nextExclamation > $pos)
                    $processedString .= substr($string, $pos, $nextExclamation-$pos);

                $processedString .= $param;

                $pos = $nextExclamation+1;

            }

            $processedString .= substr($string, $pos, strlen($string)-$pos);

            $sql .= " LIKE ? ESCAPE '!' ";
            $values[] = $processedString;

        }else{
            if(is_string($filter)){
                if(!($attr instanceof Attr_String))
                    throw new DevPanic("Illegal string value type");
            }else if(is_int($filter)){
                if(!($attr instanceof Attr_Int) && !($attr instanceof Attr_Decimal))
                    throw new DevPanic("Illegal int value type");
            }else if(is_bool($filter)){
                if(!($attr instanceof Attr_Bool))
                    throw new DevPanic("Illegal bool value type");
            }

            $sql .= " = ?";
            $values[] = $filter;
        }

        return $sql;

    }

    public function _executeInsertEntity(Entity $entity)
    {

        $query = $this->newInsert($entity->getModel());

        // TODO check autoincrement is null and rest of ids are not null

        foreach ($entity->getUpdateAttrs() as $attrName => $value){
            $query->addValue($attrName, $value);
        }

        return $query->execute();

    }

    public function _executeUpdateEntity(Entity $entity)
    {
        $query = $this->newUpdate($entity->getModel());

        foreach ($entity->getUpdateAttrs() as $attrName => $value){
            $query->addValue($attrName, $value);
        }

        $ids = $this->generate_ids_filter($entity);
        $query->filter($ids);

        return $query->execute();

    }

    public function _executeDeleteEntity(Entity $entity)
    {
        $query = $this->newDelete($entity->getModel());

        $ids = $this->generate_ids_filter($entity);
        $query->filter($ids);

        return $query->execute();
    }

    public function _notifyEntityChanged(Entity $entity)
    {
        // TODO: Implement _notifyEntityChanged() method.
    }

    /**
     * @param class-string|Model $entity
     * @return Query
     */
    public static function go_newQuery($entity){
        /** @var Manager_DB $entities */
        $entities = Find::sync(Manager_DB::class);
        return $entities->newQuery($entity);
    }

    /**
     * @param class-string|Model $entity
     * @param string $attr
     * @param string $operation
     * @return Query_Aggregation_Executor
     */
    public static function go_newQuery_Aggregation($entity, $attr, $operation)
    {
        /** @var Manager_DB $entities */
        $entities = Find::sync(Manager_DB::class);
        return $entities->newQuery_Aggregation($entity, $attr, $operation);
    }

    /**
     * @param Entity $entity
     * @return array
     */
    public function generate_ids_filter(Entity $entity)
    {
        $ids = [];
        foreach ($entity->getModel()->getAttrs() as $attr) {
            /** @var AttrExt_DB $ext */
            $ext = $attr->findExtension(AttrExt_DB::class);
            if ($ext !== null && $ext->isId())
                $ids[$attr->getId()] = $entity->getValue($attr);
        }
        return $ids;
    }

}