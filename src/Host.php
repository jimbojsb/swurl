<?php
namespace Swurl;

class Host
{
    private $parts = [];
    private $isIpAddress = false;
    private $port;

    public function __construct($hostname = null)
    {
        if ($hostname) {
            if (strpos($hostname, ":") !== false) {
                list($hostname, $port) = explode(":", $hostname);
                $this->port = $port;
            }

            if (filter_var($hostname, FILTER_VALIDATE_IP)) {
                $this->isIpAddress = true;
            }

            $hostnameParts = explode('.', $hostname);
            $this->parts = $hostnameParts;
        }
    }

    public function isIpAddress()
    {
        return $this->isIpAddress;
    }

    public function hasPort()
    {
        return is_numeric($this->port);
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPort($port = null)
    {
        if (is_numeric($port) || is_null($port)) {
            $this->port = $port;
        } else {
            throw new \InvalidArgumentException("Cannot set non-numeric port");
        }
    }

    public function isLocalHost()
    {
        $host = implode(".", $this->parts);
        if ($host == '127.0.0.1' || $host == 'localhost') {
            return true;
        }
        return false;
    }

    public function getTld()
    {
        if ($this->isIpAddress) {
            return $this->__toString();
        }
        return $this->parts[count($this->parts) - 1];
    }

    public function __toString()
    {
        $hostname =  implode(".", $this->parts);
        if ($this->hasPort()) {
            $hostname .= ":$this->port";
        }
        return $hostname;
    }

    public function addSubdomain($domain)
    {
        if ($this->isIpAddress) {
            throw new \RuntimeException("cannot do subdomain manipulation on an IP-based host");
        }
        if (count($this->parts) == 127) {
            throw new \RuntimeException("domain names may have at a maximum 127 components");
        }
        if (strlen($this->__toString()) . ".$domain" > 253) {
            throw new \RuntimeException("max length of domain names is 253 characters");
        }

        array_unshift($this->parts, $domain);
    }

    public function removeSubdomain($domain)
    {
        if ($this->isIpAddress) {
            throw new \RuntimeException("cannot do subdomain manipulation on an IP-based host");
        }
        $subdomainIndex = array_search($domain, $this->parts);
        if ($subdomainIndex !== false) {
            unset($this->parts[$subdomainIndex]);
        }
        $this->parts = array_values($this->parts);
    }

    public function hasSubdomain($domain)
    {
        if ($this->isIpAddress) {
            return false;
        }
        return array_search($domain, $this->parts) !== false;
    }
}