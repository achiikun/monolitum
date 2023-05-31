<?php

namespace monolitum\core\panic;

use monolitum\core\Node;

class DevPanic extends Panic{

    /**
     * @param string|null $message
     * @param Node|null $node
     */
    function __construct($message = null, $node = null){
        parent::__construct($message);
    }

}
