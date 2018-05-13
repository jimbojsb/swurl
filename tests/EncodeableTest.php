<?php
namespace SwUrl\Tests;

use PHPUnit\Framework\TestCase;

class EncodeableTest extends TestCase
{
    use \Swurl\Encodeable;

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

        $ignoreAlreadyEncodedString = '?facet=special_offers:Clearance%7C%7Cretailer';
        $this->assertEquals('%3Ffacet%3Dspecial_offers%3AClearance%257C%257Cretailer', $this->encode($ignoreAlreadyEncodedString, false));

        $rawString = '{{ }}';
        $this->setEncoder(false);
        $this->assertEquals($rawString, $this->encode($rawString));
    }
}