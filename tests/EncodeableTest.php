<?php
class EncodeableTest extends PHPUnit_Framework_TestCase
{
    use Swurl\Encodeable;

    public function testEncode()
    {
        $unencodedString = 'foo bar';
        $this->assertEquals('foo+bar', $this->encode($unencodedString));
        $this->setEncoder('rawurlencode');
        $this->assertEquals('foo%20bar', $this->encode($unencodedString));

        $encodedString1 = 'foo+bar';
        $encodedString2 = 'foo%20bar';
        $this->assertEquals('foo%20bar', $this->encode($encodedString2));
        $this->setEncoder('urlencode');
        $this->assertEquals('foo+bar', $this->encode($encodedString1));
    }
}