<?php
namespace Swurl;

class Query implements \IteratorAggregate, \Countable, \ArrayAccess
{
    private $params = [];
    private static $useNaiveParsing = false;

    use Encodeable;


    public function __construct($params = null)
    {
        if ($params !== null) {
            if (is_string($params)) {
                if (substr($params, 0, 1) == '?') {
                    $params = substr($params, 1);
                }
                if (self::$useNaiveParsing) {
                    $pairs = explode("&", $params);
                    $parsed = [];
                    foreach ($pairs as $pair) {
                        list($key, $val) = explode("=", $pair);
                        $parsed[$key] = $val;
                    }
                    $params = $parsed;
                } else {
                    parse_str($params, $params);
                }
            }

            if (!is_array($params)) {
                throw new \InvalidArgumentException('$params must be an array or a query string');
            }

            foreach ($params as $key => $value) {
                $this->set($key, $value);
            }
        }
    }

    public static function useNaiveParsing($naive = true)
    {
        self::$useNaiveParsing = $naive;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->params);
    }

    public function count()
    {
        return count($this->params);
    }

    public function offsetExists($offset)
    {
        return isset($this->params[$offset]);
    }

    public function offsetGet($offset)
    {
        if (isset($this->params[$offset])) {
            return $this->params[$offset];
        }
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    public function __toString()
    {
        if ($this->params) {
            $output = "?";
            $encodedParams = [];
            foreach ($this->params as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subValue) {
                        $encodedParams[$this->encode($key, false)][] = $this->encode($subValue, false);
                    }
                } else {
                    $encodedParams[$this->encode($key, false)] = $this->encode($value, false);
                }
            }

            $pairs = [];
            foreach ($encodedParams as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subValue) {
                        $pairs[] = "$key" . "[]" . "=$subValue";
                    }
                } else {
                    $pairs[] = "$key=$value";
                }
            }
            $output .= implode('&', $pairs);
            return $output;
        }
        return '';
    }

    public function set($key, $value)
    {
        $this->params[$key] = $value;
        return $this;
    }

    public function remove($key)
    {
        unset($this->params[$key]);
        return $this;
    }

    public function merge($params)
    {
        if ($params instanceof self) {
            $params = $params->getIterator()->getArrayCopy();
        }
        $newParams = array_merge($this->params, $params);
        $this->params = $newParams;
        return $this;
    }
}