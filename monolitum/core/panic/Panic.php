<?php

namespace monolitum\core\panic;

use RuntimeException;

class Panic extends RuntimeException {

    /**
     * @param string $message
     */
    function __construct($message = null){
        parent::__construct($message !== null ? $message : "");
    }

}
