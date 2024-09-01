<?php
namespace monolitum\mailer;

use monolitum\core\panic\Panic;

class MailPanic extends Panic
{
    /**
     * @param string|array $message
     */
    public function __construct($message = null)
    {
        if(is_array($message)){
            $message = json_encode($message);
        }
        parent::__construct($message);
    }

}

