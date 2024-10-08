<?php

namespace SwUrl\Tests;

use PHPUnit\Framework\TestCase;
use Swurl\AuthInfo;

class AuthInfoTest extends TestCase
{
    public function testToString()
    {
        $a = new AuthInfo;
        $a->setUsername('foo')->setPassword('bar');
        $this->assertEquals('foo:bar', $a->__toString());

        $a = new AuthInfo;
        $this->assertEquals('', $a->__toString());
    }

    public function testConstruct()
    {
        $a = new AuthInfo('foo:bar');
        $this->assertEquals('foo:bar', $a->__toString());

        $a = new AuthInfo('foo');
        $this->assertEquals('foo', $a->__toString());

        $a = new AuthInfo('foo:0');
        $this->assertEquals('foo:0', $a->__toString());

        $a = new AuthInfo('0:bar');
        $this->assertEquals('0:bar', $a->__toString());

        $a = new AuthInfo(['foo', 'bar']);
        $this->assertEquals('foo:bar', $a->__toString());

        $a = new AuthInfo('foo', 'bar');
        $this->assertEquals('foo:bar', $a->__toString());
    }
}
