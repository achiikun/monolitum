<?php

namespace backend\res;

use monolitum\core\panic\UserPanic;

class ResourceNotFoundPanic extends UserPanic {

    /**
     * @param string|null $message
     */
    function __construct($message = null){
        parent::__construct($message);
    }

}
