<?php

namespace monolitum\core\panic;

use monolitum\core\Node;

/**
 * Panic intended to represent developer mistakes in their apps.
 */
class DevPanic extends Panic{

    /**
     * @param string|null $message
     * @param Node|null $node
     */
    function __construct($message = null, $node = null){
        parent::__construct($message);
    }

}
