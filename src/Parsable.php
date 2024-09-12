<?php

namespace Swurl;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;

abstract class Parsable implements IteratorAggregate, Countable, ArrayAccess
{
    use Encodeable;

    private array $pairs = [];

    abstract protected function getParsedSeperator(): string;

    abstract protected function useAssignmentIfEmpty(): bool;

    public function __construct(string|array $parsable = null)
    {
        if ($parsable !== null) {
            if (is_string($parsable)) {
                if (substr($parsable, 0, 1) == $this->getParsedSeperator()) {
                    $parsable = substr($parsable, 1);
                }
                //manually parse to check key names for control chars later
                $rawKeys = [];
                foreach (explode('&', $parsable) as $pair) {
                    $exploded = explode('=', $pair);
                    $rawKeys[$exploded[0]] = true;
                }
                parse_str($parsable, $pairs);

                // check and repair key names with periods
                $finalParams = [];
                foreach ($pairs as $key => $value) {
                    $possibleRepairedKey = str_replace('_', '.', $key);
                    if (isset($rawKeys[$possibleRepairedKey])) {
                        $key = $possibleRepairedKey;
                    }
                    $finalParams[$key] = $value;
                }
                $pairs = $finalParams;
            } else {
                $pairs = $parsable;
            }

            if (! is_array($pairs)) {
                throw new InvalidArgumentException('$pairs must be an array or a query string');
            }

            foreach ($pairs as $key => $value) {
                $this->set($key, $value);
            }
        }
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->pairs);
    }

    public function count(): int
    {
        return count($this->pairs);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->pairs[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->pairs[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->remove($offset);
    }

    public function __toString(): string
    {
        return $this->pairs ? $this->getParsedSeperator().$this->toStringPairs($this->pairs) : '';
    }

    public function toStringPairs($pairs, $keyParent = null): string
    {
        $results = [];
        foreach ($pairs as $key => $value) {
            $keyEncoded = empty($keyParent) ? $this->encode($key, false) : $keyParent.'%5B'.(is_string($key) ? $this->encode($key, false) : '').'%5D';
            if (empty($value) || is_scalar($value)) {
                $results[] = $keyEncoded.($this->useAssignmentIfEmpty() || ! empty($value) ? '=' : '').$this->encode($value, false);
            } else {
                $results[] = $this->toStringPairs($value, $keyEncoded);
            }
        }

        return implode('&', $results);
    }

    public function set($key, $value)
    {
        $this->pairs[$key] = $value;

        return $this;
    }

    public function remove(string $key)
    {
        unset($this->pairs[$key]);

        return $this;
    }

    public function merge(array|Parsable $parsable)
    {
        if ($parsable instanceof self) {
            $parsable = $parsable->getIterator()->getArrayCopy();
        }
        $newParams = array_merge($this->pairs, $parsable);
        $this->pairs = $newParams;

        return $this;
    }
}
