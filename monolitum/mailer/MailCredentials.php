<?php

namespace monolitum\mailer;

class MailCredentials
{

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $password;

    /**
     * @param string $host
     * @param string $address
     * @param string $name
     * @param string $password
     */
    public function __construct($host, $address, $name, $password)
    {
        $this->host = $host;
        $this->address = $address;
        $this->name = $name;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


}