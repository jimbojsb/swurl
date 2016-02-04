<?php
namespace Swurl;

class Path implements \IteratorAggregate, \Countable, \ArrayAccess
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

    public function hasLeadingSlash()
    {
        return $this->hasLeadingSlash;
    }

    public function hasTrailingSlash()
    {
        return $this->hasTrailingSlash;
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

    public function offsetExists($offset)
    {
        return isset($this->parts[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->parts[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (is_numeric($offset)) {
            $this->parts[$offset] = $value;
        } else {
            throw new \RuntimeException("cannot set a non-numeric path component with array access helpers");
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->parts[$offset]);
    }


}