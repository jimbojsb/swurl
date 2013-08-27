<?php
namespace Swurl;

class Path extends \ArrayObject
{
    private $encoder;
    private $hasLeadingSlash;
    private $hasTrailingSlash;

    public function __construct($path)
    {
        if (substr($path, 0, 1) == '/') {
            $this->hasLeadingSlash = true;
        } else {
            $this->hasLeadingSlash = false;
        }

        if (substr(strrev($path), 0, 1) == '/') {
            $this->hasTrailingSlash = true;
        } else {
            $this->hasTrailingSlash = false;
        }

        $parts = explode('/', trim($path, '/'));

        $this->setEncoder('urlencode');

        parent::__construct($parts);
    }

    public function setEncoder($encoderFunction)
    {
        $this->encoder = $encoderFunction;
        return $this;
    }

    public function setHasLeadingSlash($hasLeadingSlash)
    {
        $this->hasLeadingSlash = $hasLeadingSlash;
        return $this;
    }

    public function setHasTrailingSlash($hasTrailingSlash)
    {
        $this->hasTrailingSlash = $hasTrailingSlash;
        return $this;
    }

    public function __toString()
    {
        $parts = $this->getArrayCopy();
        foreach ($parts as &$part) {
            $part = call_user_func_array($this->encoder, [$part]);
        }
        $output = "";
        if ($this->hasLeadingSlash) {
            $output .= '/';
        }
        $output .= implode('/', $parts);
        if ($this->hasTrailingSlash) {
            $output .= '/';
        }
        $output = str_replace('//', '/', $output);
        return $output;
    }
}