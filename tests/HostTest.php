<?php
use Swurl\Host;

class HostTest extends PHPUnit_Framework_TestCase
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

    public function testAddSubdomain()
    {
        $h = new Host('example.com');
        $h->addSubdomain('www');
        $this->assertTrue($h->hasSubdomain('www'));
        $this->assertEquals('www.example.com', $h->__toString());

        $h = new Host("10.0.0.1");
        try {
            $h->removeSubdomain("foo");
            $this->fail("Should have thrown runtime exception trying to modify IP hostname");
        } catch (\RuntimeException $e) {
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
}