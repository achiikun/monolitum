<?php

namespace monolitum\bootstrap\manager;

use monolitum\core\Active;
use monolitum\core\GlobalContext;
use monolitum\core\Find;
use monolitum\backend\Manager;

class Notification_Manager extends Manager implements Active
{

    const TYPE_SUCCESS = "success";
    const TYPE_WARNING = "warning";
    const TYPE_ERROR = "error";


    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param string $type
     * @param string $text
     * @return void
     */
    public function showNotification($type, $text){
        error_log($text);
    }

    /**
     * @param callable $builder
     * @return Notification_Manager
     */
    public static function add($builder)
    {
        $fc = new Notification_Manager($builder);
        GlobalContext::add($fc);
        return $fc;
    }

    /**
     * @param string $type
     * @param string $text
     * @return void
     */
    public static function go_showNotification($type, $text){
        /** @var Notification_Manager $entities */
        $entities = Find::sync(Notification_Manager::class);
        $entities->showNotification($type, $text);
    }

}