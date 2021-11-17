<?php
namespace SwUrl\Tests;

use Swurl\Host;
use PHPUnit\Framework\TestCase;

class HostTest extends TestCase
{
    public function testToString()
    {
        $h = new Host('www.example.com');
        $this->assertEquals('www.example.com', $h->__toString());
        $h = new Host;
        $this->assertEquals('', $h->__toString());
    }

    public function testGetTld()
    {
        $h = new Host('www.example.com');
        $this->assertEquals('com', $h->getTld());
    }

    public function testHasSubdomain()
    {
        $h = new Host('www.example.com');
        $this->assertTrue($h->hasSubdomain('www'));
    }

    public function testDoesNotHaveSubdomain()
    {
        $h = new Host('www.example.com');
        $this->assertFalse($h->hasSubdomain('link'));
    }

    public function testHasAnySubdomain()
    {
        $h = new Host('www.example.com');
        $this->assertTrue($h->hasSubdomain());
    }

    public function testDoesNotHaveAnySubdomain()
    {
        $h = new Host('example.com');
        $this->assertFalse($h->hasSubdomain());
    }

    public function dataForTestAddSubDomains()
    {
        return [
            ['www'],
            ['70684e54'],
            ['$%#@'],
        ];
    }

    /**
     * @param $domain
     *
     * @dataProvider dataForTestAddSubDomains
     */
    public function testAddSubdomain($domain)
    {
        $h = new Host('example.com');
        $h->addSubdomain($domain);
        $this->assertTrue($h->hasSubdomain($domain));
        $this->assertEquals("$domain.example.com", $h->__toString());
    }

    public function testAddSubdomainToIp()
    {
        $h = new Host("10.0.0.1");
        try {
            $domain = 'www';
            $h->addSubdomain($domain);
            $this->fail("Should have thrown runtime exception trying to modify IP hostname");
        } catch (\RuntimeException $e) {
            $this->assertFalse($h->hasSubdomain($domain));
            $this->assertStringContainsString('IP-based host', $e->getMessage());
        }
    }

    public function testRemoveSubdomain()
    {
        $h = new Host('www.example.com');
        $h->removeSubdomain('www');
        $this->assertFalse($h->hasSubdomain('www'));
        $this->assertEquals('example.com', $h->__toString());

        $h = new Host("10.0.0.1");
        try {
            $h->removeSubdomain("foo");
            $this->fail("Should have thrown runtime exception trying to modify IP hostname");
        } catch (\RuntimeException $e) {
        }
    }

    public function testIsIpAddress()
    {
        $h = new Host("www.example.com");
        $this->assertFalse($h->isIpAddress());

        $h = new Host("127.0.0.1");
        $this->assertTrue($h->isIpAddress());

        $h = new Host("127.0.0.1:10007");
        $this->assertTrue($h->isIpAddress());
    }

    public function testHostWithPort()
    {
        $h = new Host("www.example.com:8080");
        $this->assertTrue($h->hasPort());
        $this->assertEquals("8080", $h->getPort());

        $h->setPort(9090);
        $this->assertEquals(9090, $h->getPort());

        $h->setPort();
        $this->assertFalse($h->hasPort());
        $this->assertEquals(null, $h->getPort());

        try {
            $h->setPort("Foo");
            $this->fail("Expected InvalidArgumentException trying to set non-numeric port");
        } catch (\InvalidArgumentException $e) {
        }
    }

    public function testIsLocalHost()
    {
        $h = new Host("127.0.0.1");
        $this->assertTrue($h->isLocalHost());

        $h = new Host("localhost");
        $this->assertTrue($h->isLocalHost());

        $h = new Host("10.0.0.1");
        $this->assertFalse($h->isLocalHost());

        $h = new Host("127.0.0.1:8080");
        $this->assertTrue($h->isLocalHost());

        $h = new Host("localhost:8080");
        $this->assertTrue($h->isLocalHost());

        $h = new Host("example.com");
        $this->assertFalse($h->isLocalHost());
    }
}