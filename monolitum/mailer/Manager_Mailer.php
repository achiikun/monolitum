<?php

namespace monolitum\mailer;

use Closure;
use monolitum\backend\crypto\AsymmetricKey;
use monolitum\backend\crypto\SymmetricKey;
use monolitum\backend\Manager;
use monolitum\backend\res\Manager_Res_Resolver;
use monolitum\core\GlobalContext;
use monolitum\core\panic\DevPanic;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use function monolitum\backend\crypto\mcrypt_create_iv;

class Manager_Mailer extends Manager
{

    /**
     * @var MailCredentials[]
     */
    private $mailCredentials = [];

    /**
     * @var SMTP[]
     */
    private $smtps = [];

    /**
     * @param callable|null $builder
     */
    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param string $name
     * @param MailCredentials $mailCredentials
     * @return $this
     */
    public function addMailCredentials($name, $mailCredentials)
    {
        $this->mailCredentials[$name] = $mailCredentials;
        return $this;
    }

    /**
     * @param $digestAlg
     * @param $bits
     * @return PHPMailer
     */
    public function createNewMail($keyname)
    {

        if (array_key_exists($keyname, $this->mailCredentials)) {

            $mailCredentials = $this->mailCredentials[$keyname];

            if (!array_key_exists($keyname, $this->smtps) || !$this->smtps[$keyname]->connected()) {
                $smtp = new SMTP();

                if(!$smtp->connect($mailCredentials->getHost())){
                    throw new MailPanic();
                }
                if(!$smtp->authenticate($mailCredentials->getAddress(), $mailCredentials->getPassword())){
                    throw new MailPanic();
                }

                $this->smtps[$keyname] = $smtp;
            }else{
                $smtp = $this->smtps[$keyname];
            }

            $phpMailer = new PHPMailer();
            $phpMailer->setSMTPInstance($smtp);
            try {
                $phpMailer->setFrom($mailCredentials->getAddress(), $mailCredentials->getName());
            } catch (Exception $e) {
                throw new MailPanic();
            }

            return $phpMailer;

        }else{
            throw new MailPanic();
        }

    }

    /**
     * @param callable|null $builder
     */
    public static function add($builder)
    {
        GlobalContext::add(new Manager_Mailer($builder));
    }

}