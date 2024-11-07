<?php

namespace Swurl;

class AuthInfo
{
    private $username;

    private $password;

    public function __construct($authInfo = null)
    {
        $authItems = [];
        if (func_num_args() == 2) {
            $authItems = func_get_args();
        } elseif (is_string($authInfo)) {
            $authItems = explode(':', $authInfo);
        } elseif (is_array($authInfo)) {
            $authItems = $authInfo;
        }

        $this->setUsername(array_shift($authItems));
        $this->setPassword(array_shift($authItems));
    }

    public function setUsername(?string $username)
    {
        if ($this->valid($username) === true) {
            $this->username = $username;
        }

        return $this;
    }

    public function setPassword(?string $password)
    {
        if ($this->valid($password) === true) {
            $this->password = $password;
        }

        return $this;
    }

    public function __toString(): string
    {
        if ($this->username === null) {
            return '';
        }

        return "$this->username".($this->password !== null ? ":$this->password" : '');
    }

    protected function valid($authItem)
    {
        return $authItem !== false && $authItem !== null && $authItem !== '';
    }
}
