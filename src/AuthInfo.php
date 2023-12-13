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

        if (count($authItems) > 0) {
            $this->setUsername(array_shift($authItems));
        }
        if (count($authItems) > 0) {
            $this->setPassword(array_shift($authItems));
        }
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
        
        $result = "$this->username";    
        if ($this->password) {
            $result.= ":$this->password";
        }

        return $result;
    }

   protected function valid($authItem)
   {
       return $authItem !== false && $authItem !== null && $authItem !== '';
   }
}
