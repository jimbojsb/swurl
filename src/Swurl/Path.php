<?php
namespace Swurl;

class Path implements \IteratorAggregate, \Countable
{
    private $hasLeadingSlash = false;
    private $hasTrailingSlash = false;
    private $parts = [];

    use Encodeable;

    public function __construct($path = null)
    {
        if ($path) {
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
            $this->parts = $parts;
        }

        $this->setEncoder('urlencode');
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

    public function getIterator()
    {
        return new \ArrayIterator($this->parts);
    }

    public function count()
    {
        return count($this->parts);
    }

    public function appendPath($path)
    {
        $this->parts[] = $path;
    }

    public function prependPath($path)
    {
        array_unshift($this->parts, $path);
    }

    public function __toString()
    {
        $parts = $this->parts;
        foreach ($parts as &$part) {
            $part = $this->encode($part);
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