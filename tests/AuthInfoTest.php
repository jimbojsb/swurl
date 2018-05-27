<?php
namespace SwUrl\Tests;

use Swurl\AuthInfo;
use PHPUnit\Framework\TestCase;

class AuthInfoTest extends TestCase
{
    public function testToString()
    {
        $a = new AuthInfo();
        $a->setUsername('foo')->setPassword('bar');
        $this->assertEquals("foo:bar", $a->__toString());

        $a = new AuthInfo;
        $this->assertEquals('', $a->__toString());
    }

    public function testConstruct()
    {
        $a = new AuthInfo("foo:bar");
        $this->assertEquals("foo:bar", $a->__toString());

        $a = new AuthInfo(['foo', 'bar']);
        $this->assertEquals("foo:bar", $a->__toString());

        $a = new AuthInfo('foo', 'bar');
        $this->assertEquals("foo:bar", $a->__toString());
    }
}