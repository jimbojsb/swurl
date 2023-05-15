<?php

namespace SwUrl\Tests;

use PHPUnit\Framework\TestCase;
use Swurl\Url;

class UrlTest extends TestCase
{
    public function testToString()
    {
        $u = new Url('http://www.example.com/?foo=bar#baz');
        $this->assertEquals('http://www.example.com/?foo=bar#baz', $u->__toString());
    }

    public function testEquals()
    {
        $u = new Url('http://example.com');
        $u2 = new Url('http://example.com');
        $this->assertTrue($u->equals($u2));

        $u2->getHost()->addSubdomain('www');
        $this->assertFalse($u->equals($u2));

        $u3 = 'http://example.com';

        $this->assertTrue($u->equals($u3));

        // test deep cloning
        $u4 = new Url('http://www.example.com/foo');
        $u5 = clone $u4;
        $u5->getPath()->setHasTrailingSlash(true);
        $this->assertFalse($u4->equals($u5));
    }

    public function testPartialUrls()
    {
        $u = new Url('/foo/bar');
        $u->getPath()->setHasTrailingSlash(true);
        $this->assertEquals('/foo/bar/', $u->__toString());
    }

    public function testAddingToPartialUrls()
    {
        $u = new Url('/foo/bar');
        $u->setHost('www.example.com');
        $this->assertEquals('www.example.com/foo/bar', $u->__toString());

        $u->getQuery()->set('foo', 'bar');
        $this->assertEquals('www.example.com/foo/bar?foo=bar', $u->__toString());
    }

    public function testCurrent()
    {
        $_SERVER['REQUEST_URI'] = '/foo/bar/baz?foo=bar&baz=foo';
        $_SERVER['HTTP_HOST'] = 'example.com';

        $url = Url::current();
        $this->assertEquals('http://example.com/foo/bar/baz?foo=bar&baz=foo', $url->__toString());
    }

    public function testSetUri()
    {
        $u = new Url;
        $u->setUri('/foo/bar?bar=baz#frag');
        $this->assertEquals('/foo/bar?bar=baz#frag', $u->__toString());
    }

    public function testRelativePathWithHost()
    {
        $url = new \Swurl\Url('test');
        $url->setHost('google.com');
        $this->assertEquals('google.com/test', $url->__toString());
    }

    public function testSchemeWithNoHost()
    {
        $url = new \Swurl\Url('/foo/bar.jpg');
        $url->setScheme('http');
        $this->assertEquals('/foo/bar.jpg', $url->__toString());
    }

    public function testSchemelessUrls()
    {
        $url = new \Swurl\Url('/foo/bar.jpg');
        $url->makeSchemeless();

        $this->assertEquals('/foo/bar.jpg', $url->__toString());

        $url->setHost('example.com');
        $this->assertEquals('//example.com/foo/bar.jpg', $url->__toString());

        $url->setScheme('http');
        $this->assertEquals('http://example.com/foo/bar.jpg', $url->__toString());

        $url = new Url('//foo.com/bar/bar.jpg');
        $this->assertTrue($url->isSchemeless());
        $this->assertEquals('//foo.com/bar/bar.jpg', $url->__toString());

        $url->setScheme('http');
        $this->assertEquals('http://foo.com/bar/bar.jpg', $url->__toString());
    }

    public function testHostWithPort()
    {
        $url = new \Swurl\Url('http://www.example.com:8080');
        $this->assertTrue($url->getHost()->hasPort());
        $this->assertEquals(8080, $url->getHost()->getPort());
    }
}
