<?php
namespace monolitum\auth;

class AuthPanic_NotFound extends AuthPanic
{

    public function __construct($message = "Not Found")
    {
        parent::__construct($message);
    }

}

