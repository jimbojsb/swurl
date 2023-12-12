<?php

namespace Swurl;

class AuthInfo
{
    private $username;

    private $password;

    public function __construct($authInfo = null)
    {
        if (func_num_args() == 2) {
            [$this->username, $this->password] = func_get_args();
        } elseif (is_string($authInfo)) {
            [$this->username, $this->password] = explode(':', $authInfo);
        } elseif (is_array($authInfo)) {
            [$this->username, $this->password] = $authInfo;
        }
    }

    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function __toString(): string
    {
        $result = '';
        if (empty($this->username) === false) {
            $result = "$this->username";
        }
        if (empty($this->password) === false) {
            $result.= ":$this->password";
        }

        return $result;
    }
}
