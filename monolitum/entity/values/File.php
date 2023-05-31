<?php

namespace monolitum\entity\values;

class File
{

    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var int */
    private $size;

    /** @var string */
    private $temp_name;

    /**
     * @param string $name
     * @param string $type
     * @param int $size
     * @param string $temp_name
     */
    public function __construct($name, $type, $size, $temp_name)
    {
        $this->name = $name;
        $this->type = $type;
        $this->size = $size;
        $this->temp_name = $temp_name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getTempName()
    {
        return $this->temp_name;
    }


}