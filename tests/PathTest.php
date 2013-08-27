<?php
use Swurl\Path;

class PathTest extends PHPUnit_Framework_TestCase
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
    }
}