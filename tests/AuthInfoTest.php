<?php
use Swurl\AuthInfo;

class AuthInfoTest extends PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $a = new AuthInfo();
        $a->setUsername('foo')->setPassword('bar');
        $this->assertEquals("foo:bar", $a->__toString());
    }
}