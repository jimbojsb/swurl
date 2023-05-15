<?php

namespace Swurl;

class Scheme
{
    private $scheme;

    public function __construct(string $scheme = null)
    {
        $this->scheme = $scheme;
    }

    public function setScheme(string $scheme)
    {
        $this->scheme = $scheme;
    }

    public function __toString(): string
    {
        return $this->scheme ?: '';
    }

    public function makeSecure(): Scheme
    {
        $this->scheme .= 's';

        return $this;
    }

    public function makeInsecure(): Scheme
    {
        if ($this->isSecure()) {
            $this->scheme = substr($this->scheme, 0, strlen($this->scheme) - 1);
        }

        return $this;
    }

    public function isSecure(): bool
    {
        return substr(strrev($this->scheme), 0, 1) == 's';
    }
}
