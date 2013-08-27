<?php
namespace Swurl;

class Host
{
    private $parts = [];

    public function __construct($hostname = null)
    {
        if ($hostname) {
            $hostnameParts = explode('.', $hostname);
            $this->parts = $hostnameParts;
        }
    }

    public function getTld()
    {
        return $this->parts[count($this->parts) - 1];
    }

    public function __toString()
    {
        return implode(".", $this->parts);
    }

    public function addSubdomain($domain)
    {
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
        $subdomainIndex = array_search($domain, $this->parts);
        if ($subdomainIndex !== false) {
            unset($this->parts[$subdomainIndex]);
        }
        $this->parts = array_values($this->parts);
    }

    public function hasSubdomain($domain)
    {
        return array_search($domain, $this->parts) !== false;
    }
}