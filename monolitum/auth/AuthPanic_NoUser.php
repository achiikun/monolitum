<?php
namespace monolitum\auth;

class AuthPanic_NoUser extends AuthPanic
{

    public function __construct($message = null)
    {
        parent::__construct($message);
    }

}

