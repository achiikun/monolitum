<?php

namespace monolitum\core\panic;

use monolitum\core\panic\Panic;

class UserPanic extends Panic {

    /**
     * @param string|null $message
     */
    function __construct($message = null){
        parent::__construct($message);
    }

}
