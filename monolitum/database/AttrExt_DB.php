<?php
namespace monolitum\database;

use monolitum\entity\AttrExt;

class AttrExt_DB extends AttrExt
{
    /**
     * @var bool
     */
    public $id;

    /**
     * @var bool
     */
    public $autoincrement;

    /**
     * @return $this
     */
    public function id(){
        $this->id = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function autoincrementId(){
        $this->id = true;
        $this->autoincrement = true;
        return $this;
    }

    /**
     * @return bool
     */
    public function isId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isAutoincrement()
    {
        return $this->autoincrement;
    }

    public static function from()
    {
        return new AttrExt_DB();
    }
}

