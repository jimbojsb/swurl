<?php
namespace Swurl;

class AuthInfo
{
    private $username;
    private $password;

    public function __construct($username = null, $password = null)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function __toString()
    {
        return "$this->username:$this->password";
    }
}