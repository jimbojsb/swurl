<?php
namespace SwUrl\Tests;

use Swurl\Fragment;
use PHPUnit\Framework\TestCase;

class FragmentTest extends TestCase
{
    public function testToString()
    {
        $f = new Fragment("foo");
        $this->assertEquals("#foo", $f->__toString());
        $f = new Fragment("#foo");
        $this->assertEquals("#foo", $f->__toString());
        $f = new Fragment;
        $this->assertEquals('', $f->__toString());
        $f = new Fragment('#foo&param1=value1&param2=&param3=value3');
        $this->assertEquals('#foo&param1=value1&param2&param3=value3', $f->__toString());
    }
}
