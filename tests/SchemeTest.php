<?php

namespace SwUrl\Tests;

use PHPUnit\Framework\TestCase;
use Swurl\Scheme;

class SchemeTest extends TestCase
{
    public function testSecure()
    {
        $s = new Scheme('http');
        $this->assertFalse($s->isSecure());
        $this->assertEquals('http', $s->__toString());

        $s->makeSecure();
        $this->assertTrue($s->isSecure());
        $this->assertEquals('https', $s->__toString());

        $s->makeInsecure();
        $this->assertFalse($s->isSecure());
        $this->assertEquals('http', $s->__toString());
    }
}
