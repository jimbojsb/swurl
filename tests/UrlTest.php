<?php
use Swurl\Url;

class UrlTest extends PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $u = new Url("http://www.example.com/?foo=bar#baz");
        $this->assertEquals("http://www.example.com/?foo=bar#baz", $u->__toString());
    }

    public function testEquals()
    {
        $u = new Url("http://example.com");
        $u2 = new Url("http://example.com");
        $this->assertTrue($u->equals($u2));

        $u2->getHost()->addSubdomain('www');
        $this->assertFalse($u->equals($u2));

        $u3 = "http://example.com";

        $this->assertTrue($u->equals($u3));
    }
}