<?php

namespace monolitum\core\panic;

class UserPanic extends Panic {

    /**
     * @param string|null $message
     */
    function __construct($message = null){
        parent::__construct($message);
    }

}
