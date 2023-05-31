<?php
namespace monolitum\auth;

class AuthPanic_NoPermissions extends AuthPanic
{

    public function __construct($message = "No Permissions")
    {
        parent::__construct($message);
    }

}

