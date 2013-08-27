<?php
namespace Swurl;

class Query extends \ArrayObject
{
    public function __construct($params = null)
    {
        if ($params !== null) {
            if (is_string($params)) {
                if (substr($params, 0, 1) == '?') {
                    $params = substr($params, 1);
                }
                parse_str($params, $params);
            }

            if (!is_array($params)) {
                throw new \InvalidArgumentException('$params must be an array or a query string');
            }
        }
        parent::__construct($params ?: []);
    }

    public function __toString()
    {
        return '?' . http_build_query($this);
    }

    public function merge($params)
    {
        if ($params instanceof self) {
            $params = $params->getArrayCopy();
        }
        $newParams = array_merge($this->getArrayCopy(), $params);
        $this->exchangeArray($newParams);
        return $this;
    }
}