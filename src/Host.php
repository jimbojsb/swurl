<?php

namespace Swurl;

class Host
{
    private array $parts = [];

    private bool $isIpAddress = false;

    private ?int $port = null;

    public function __construct(string $hostname = null)
    {
        if ($hostname) {
            if (strpos($hostname, ':') !== false) {
                [$hostname, $port] = explode(':', $hostname);
                $this->port = $port;
            }

            if (filter_var($hostname, FILTER_VALIDATE_IP)) {
                $this->isIpAddress = true;
            }

            $hostnameParts = explode('.', $hostname);
            $this->parts = $hostnameParts;
        }
    }

    public function isIpAddress(): bool
    {
        return $this->isIpAddress;
    }

    public function hasPort(): bool
    {
        return is_numeric($this->port);
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(int $port = null)
    {
        if (is_numeric($port) || is_null($port)) {
            $this->port = $port;
        } else {
            throw new \InvalidArgumentException('Cannot set non-numeric port');
        }
    }

    public function isLocalHost(): bool
    {
        $host = implode('.', $this->parts);
        if ($host == '127.0.0.1' || $host == 'localhost') {
            return true;
        }

        return false;
    }

    public function getTld(): string
    {
        if ($this->isIpAddress) {
            return $this->__toString();
        }

        return $this->parts[count($this->parts) - 1];
    }

    public function __toString(): string
    {
        $hostname = implode('.', $this->parts);
        if ($this->hasPort()) {
            $hostname .= ":$this->port";
        }

        return $hostname;
    }

    public function addSubdomain(string $domain)
    {
        if ($this->isIpAddress) {
            throw new \RuntimeException('cannot do subdomain manipulation on an IP-based host');
        }
        if (count($this->parts) == 127) {
            throw new \RuntimeException('domain names may have at a maximum 127 components');
        }
        if (strlen($this->__toString().".$domain") > 253) {
            throw new \RuntimeException('max length of domain names is 253 characters');
        }

        array_unshift($this->parts, $domain);
    }

    public function removeSubdomain(string $domain)
    {
        $subdomainIndex = array_search($domain, $this->parts);
        $this->removeSubdomainByIndex($subdomainIndex);
    }

    public function removeSubdomainByIndex(int $subdomainIndex)
    {
        if ($this->isIpAddress) {
            throw new \RuntimeException('cannot do subdomain manipulation on an IP-based host');
        }
        if ($subdomainIndex !== false && array_key_exists($subdomainIndex, $this->parts) === true) {
            unset($this->parts[$subdomainIndex]);
        }
        $this->parts = array_values($this->parts);
    }

    public function hasSubdomain(string $domain = null): bool
    {
        if ($this->isIpAddress) {
            return false;
        }

        if ($domain === null) {
            return count($this->parts) > 2;
        }

        return array_search($domain, $this->parts) !== false;
    }
}
