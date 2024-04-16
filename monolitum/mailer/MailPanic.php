<?php
namespace monolitum\mailer;

use monolitum\core\panic\Panic;

class MailPanic extends Panic
{
    public function __construct($message = null)
    {
        parent::__construct($message);
    }

}

