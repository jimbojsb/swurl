<?php
namespace Swurl;

class Scheme
{
    private $scheme;

    public function __construct($scheme = null)
    {
        $this->scheme = $scheme;
    }

    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }

    public function __toString()
    {
        return $this->scheme ?: "";
    }

    public function makeSecure()
    {
        $this->scheme .= "s";
        return $this;
    }

    public function makeInsecure()
    {
        if ($this->isSecure()) {
            $this->scheme = substr($this->scheme, 0, strlen($this->scheme) - 1);
        }
        return $this;
    }

    public function isSecure()
    {
        return substr(strrev($this->scheme), 0, 1) == 's';
    }
}