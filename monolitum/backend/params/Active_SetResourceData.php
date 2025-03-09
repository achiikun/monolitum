<?php

namespace monolitum\backend\params;

use monolitum\backend\params\Link;
use monolitum\backend\params\Path;
use monolitum\core\Active;
use monolitum\core\panic\DevPanic;

class Active_SetResourceData implements Active
{

    /**
     * @var string
     */
    private $dataBase64;

    /**
     * @var callable
     */
    private $writerFunction;

    /**
     * @param string $dataBase64
     * @param callable $writerFunction
     */
    private function __construct($dataBase64, $writerFunction)
    {
        $this->dataBase64 = $dataBase64;
        $this->writerFunction = $writerFunction;
    }

    /**
     * @return string
     */
    public function getDataBase64()
    {
        return $this->dataBase64;
    }

    /**
     * @return callable
     */
    public function getWriterFunction()
    {
        return $this->writerFunction;
    }

    function onNotReceived()
    {
        throw new DevPanic("No redirect manager.");
    }

    /**
     * @param string $dataBase64
     * @return Active_SetResourceData
     */
    public static function fromBase64Data($dataBase64){
        return new Active_SetResourceData($dataBase64, null);
    }

    /**
     * @param string $dataBase64
     * @return Active_SetResourceData
     */
    public static function fromWriterFunction($writerFunction){
        return new Active_SetResourceData(null, $writerFunction);
    }
}
