<?php
use Swurl\Fragment;

class FragmentTest extends PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $f = new Fragment("foo");
        $this->assertEquals("#foo", $f->__toString());
        $f = new Fragment("#foo");
        $this->assertEquals("#foo", $f->__toString());
        $f = new Fragment;
        $this->assertEquals('', $f->__toString());
    }
}