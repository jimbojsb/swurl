<?php

namespace Swurl;

class Url
{
    private ?Fragment $fragment = null;

    private ?Host $host = null;

    private ?Scheme $scheme = null;

    private ?Query $query = null;

    private ?Path $path = null;

    private ?AuthInfo $authInfo = null;

    private bool $isSchemeless = false;

    public function __construct(?string $url = null)
    {
        if ($url) {

            $parts = parse_url($url);

            if (isset($parts['scheme'])) {
                $this->setScheme(new Scheme($parts['scheme']));
            } elseif (substr($url, 0, 2) === '//') {
                $this->makeSchemeless();
            }

            if (isset($parts['user']) === true) {
                $this->setAuthInfo(new AuthInfo($parts['user'], $parts['pass'] ?? null));
            }

            if (isset($parts['host'])) {
                $this->setHost(new Host($parts['host']));
                if (isset($parts['port'])) {
                    $this->host->setPort($parts['port']);
                }
            }

            if (isset($parts['path'])) {
                $this->setPath(new Path($parts['path']));
            }

            if (isset($parts['query'])) {
                $this->setQuery(new Query($parts['query']));
            }

            if (isset($parts['fragment'])) {
                $this->setFragment(new Fragment($parts['fragment']));
            }
        }
    }

    public function makeSchemeless(): void
    {
        $this->isSchemeless = true;
    }

    public function isSchemeless(): bool
    {
        return $this->isSchemeless;
    }

    public function setPath(string $path)
    {
        if (is_string($path)) {
            $path = new Path($path);
        }
        $this->path = $path;
    }

    public function setQuery(Query|string|array $query)
    {
        if (! ($query instanceof Query)) {
            $query = new Query($query);
        }
        $this->query = $query;
    }

    public function setHost(string $host)
    {
        if (! ($host instanceof Host)) {
            $host = new Host($host);
        }
        $this->host = $host;
    }

    public function setAuthInfo(string|AuthInfo|array $authInfo)
    {
        if (! ($authInfo instanceof AuthInfo)) {
            $authInfo = new AuthInfo($authInfo);
        }
        $this->authInfo = $authInfo;
    }

    public function setFragment(string|Fragment|array $fragment)
    {
        if (! ($fragment instanceof Fragment)) {
            $fragment = new Fragment($fragment);
        }
        $this->fragment = $fragment;
    }

    public function setScheme(string|Scheme $scheme)
    {
        if (! ($scheme instanceof Scheme)) {
            $scheme = new Scheme($scheme);
        }
        $this->scheme = $scheme;
        $this->isSchemeless = false;
    }

    public function equals($url): bool
    {
        return $this->__toString() == "$url";
    }

    public function setEncoder(string $encoder)
    {
        $this->query->setEncoder($encoder);
        $this->path->setEncoder($encoder);
    }

    public function __toString(): string
    {
        $output = '';

        if ($this->host) {
            if ($this->isSchemeless) {
                $output .= '//';
            } elseif ($this->scheme) {
                $output .= $this->scheme;
                $output .= '://';
            }
        }

        if ($this->authInfo) {
            $output .= $this->authInfo;
        }

        if ($this->host) {
            $output .= $this->host;
        }

        if ($this->path) {
            if ($this->host) {
                if (! $this->path->hasLeadingSlash()) {
                    $output .= '/';
                }
            }
            $output .= $this->path;
        }

        if ($this->query) {
            $output .= $this->query;
        }

        if ($this->fragment) {
            $output .= $this->fragment;
        }

        return $output;
    }

    public function __clone()
    {
        if ($this->scheme) {
            $this->scheme = clone $this->scheme;
        }

        if ($this->authInfo) {
            $this->authInfo = clone $this->authInfo;
        }

        if ($this->host) {
            $this->host = clone $this->host;
        }

        if ($this->path) {
            $this->path = clone $this->path;
        }

        if ($this->query) {
            $this->query = clone $this->query;
        }

        if ($this->fragment) {
            $this->fragment = clone $this->fragment;
        }
    }

    public function getAuthInfo(): AuthInfo
    {
        if (! $this->authInfo) {
            $this->authInfo = new AuthInfo;
        }

        return $this->authInfo;
    }

    public function getFragment(): Fragment
    {
        if (! $this->fragment) {
            $this->fragment = new Fragment;
        }

        return $this->fragment;
    }

    public function getHost(): Host
    {
        if (! $this->host) {
            $this->host = new Host;
        }

        return $this->host;
    }

    public function getPath(): Path
    {
        if (! $this->path) {
            $this->path = new Path;
        }

        return $this->path;
    }

    public function getQuery(): Query
    {
        if (! $this->query) {
            $this->query = new Query;
        }

        return $this->query;
    }

    public function getScheme(): Scheme
    {
        if (! $this->scheme) {
            $this->scheme = new Scheme;
        }

        return $this->scheme;
    }

    public function setUri(string $uri)
    {
        $parts = parse_url($uri);
        if ($parts['path']) {
            $this->setPath($parts['path']);
        }
        if ($parts['query']) {
            $this->setQuery($parts['query']);
        }
        if ($parts['fragment']) {
            $this->setFragment($parts['fragment']);
        }
    }

    public static function current(): Url
    {
        $url = new self($_SERVER['REQUEST_URI']);
        $url->setHost($_SERVER['HTTP_HOST']);
        if (isset($_SERVER['HTTPS'])) {
            $url->setScheme('https');
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $url->setScheme('https');
        } else {
            $url->setScheme('http');
        }

        return $url;
    }
}
