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
    }

    public function testRemoveSubdomain()
    {
        $h = new Host('www.example.com');
        $h->removeSubdomain('www');
        $this->assertFalse($h->hasSubdomain('www'));
        $this->assertEquals('example.com', $h->__toString());
    }
}