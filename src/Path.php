<?php

namespace Swurl;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Traversable;

class Path implements ArrayAccess, Countable, IteratorAggregate
{
    use Encodeable;

    private bool $hasLeadingSlash = false;

    private bool $hasTrailingSlash = false;

    private array $parts = [];

    public function __construct(?string $path = null)
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

    public function setHasLeadingSlash(bool $hasLeadingSlash)
    {
        $this->hasLeadingSlash = $hasLeadingSlash;

        return $this;
    }

    public function setHasTrailingSlash(bool $hasTrailingSlash)
    {
        $this->hasTrailingSlash = $hasTrailingSlash;

        return $this;
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->parts);
    }

    public function count(): int
    {
        return count($this->parts);
    }

    public function appendPath(string $path)
    {
        $this->parts[] = $path;
    }

    public function prependPath(string $path)
    {
        array_unshift($this->parts, $path);
    }

    public function hasLeadingSlash(): bool
    {
        return $this->hasLeadingSlash;
    }

    public function hasTrailingSlash(): bool
    {
        return $this->hasTrailingSlash;
    }

    public function __toString(): string
    {
        $parts = $this->parts;
        foreach ($parts as &$part) {
            $part = $this->encode($part);
        }
        $output = '';
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

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->parts[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->parts[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_numeric($offset)) {
            $this->parts[$offset] = $value;
        } else {
            throw new \RuntimeException('cannot set a non-numeric path component with array access helpers');
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->parts[$offset]);
    }
}
