<?php
namespace monolitum\auth;

use monolitum\core\panic\Panic;

class AuthPanic extends Panic
{
    public function __construct($message = null)
    {
        parent::__construct($message);
    }

}

