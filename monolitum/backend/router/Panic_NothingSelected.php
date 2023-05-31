<?php

namespace monolitum\backend\router;

use monolitum\core\Node;
use monolitum\core\panic\Panic;

class Panic_NothingSelected extends Panic {

    /**
     * @param string $message
     * @param Node|null $node
     */
    function __construct($message = null, $node = null){
        parent::__construct($message);
    }

}
