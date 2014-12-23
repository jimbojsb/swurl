<?php
namespace Swurl;

class AuthInfo
{
    private $username;
    private $password;

    public function __construct($authInfo = null)
    {
        if (func_num_args() == 2) {
            list($this->username, $this->password) = func_get_args();
        } else if (is_string($authInfo)) {
            list($this->username, $this->password) = explode(":", $authInfo);
        } else if (is_array($authInfo)) {
            list($this->username, $this->password) = $authInfo;
        }
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
        if ($this->username || $this->password) {
            return "$this->username:$this->password";
        }
        return '';
    }
}