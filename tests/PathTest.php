<?php
namespace SwUrl\Tests;

use Swurl\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    public function testToString()
    {
        $p = new Path('/foo/bar/baz');
        $this->assertEquals('/foo/bar/baz', $p->__toString());

        $p = new Path;
        $this->assertEquals('', $p->__toString());
    }

    public function testEncoder()
    {
        $p = new Path('/foo bar/');
        $this->assertEquals('/foo+bar/', $p->__toString());
        $p->setEncoder('rawurlencode');
        $this->assertEquals('/foo%20bar/', $p->__toString());
    }

    public function testAddPath()
    {
        $p = new Path('/foo/bar');
        $p->appendPath('baz');
        $this->assertEquals('/foo/bar/baz', $p->__toString());

        $p = new Path('/foo/bar');
        $p->prependPath('baz');
        $this->assertEquals('/baz/foo/bar', $p->__toString());
    }

    public function testLeadingTrailingSlash()
    {
        $p = new Path('/foo/bar');

        $p->setHasLeadingSlash(false);
        $this->assertEquals('foo/bar', $p->__toString());

        $p->setHasTrailingSlash(true);
        $this->assertEquals('foo/bar/', $p->__toString());

        $p = new Path('foo');
        $this->assertFalse($p->hasLeadingSlash());
        $this->assertFalse($p->hasTrailingSlash());

        $p = new Path('foo/');
        $this->assertFalse($p->hasLeadingSlash());
        $this->assertTrue($p->hasTrailingSlash());

        $p = new Path('/foo');
        $this->assertTrue($p->hasLeadingSlash());
        $this->assertFalse($p->hasTrailingSlash());
    }

    public function testArrayAccess()
    {
        $p = new Path('/foo/bar');
        $this->assertEquals("foo", $p[0]);

        $p->appendPath("baz");
        $this->assertEquals("/foo/bar/baz", $p->__toString());

        $p[0] = "test";
        $this->assertEquals("/test/bar/baz", $p->__toString());

        unset($p[0]);
        $this->assertEquals("/bar/baz", $p->__toString());

        try {
            $p["test"] = "foo";
            $this->fail("trying to manipulate a url by array access string keys is not allowed and should throw an exception");
        } catch (\RuntimeException $e) {
        }
    }
}