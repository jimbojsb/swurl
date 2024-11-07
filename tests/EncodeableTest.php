<?php

namespace SwUrl\Tests;

use PHPUnit\Framework\TestCase;

class EncodeableTest extends TestCase
{
    use \Swurl\Encodeable;

    public function testUrlencoder()
    {
        $this->setEncoder('urlencode');

        $unencodedString = 'https://foo.bar/foo/bar?foo=bar&bar=foo#foo=bar';
        $encodedString = urlencode($unencodedString);

        $this->assertEquals($encodedString, $this->encode($unencodedString));
        $this->assertEquals($encodedString, $this->encode($encodedString));
        $this->assertEquals(urlencode($encodedString), $this->encode($encodedString, false));
    }

    public function testRawurlencoder()
    {
        $this->setEncoder('rawurlencode');

        $unencodedString = 'https://foo.bar/foo/bar?foo=bar&bar=foo#foo=bar';
        $encodedString = rawurlencode($unencodedString);

        $this->assertEquals($encodedString, $this->encode($unencodedString));
        $this->assertEquals($encodedString, $this->encode($encodedString));
        $this->assertEquals(rawurlencode($encodedString), $this->encode($encodedString, false));
    }

    public function testFalseEncoder()
    {
        $this->setEncoder(false);

        $rawString = '{{ }}';
        $this->assertEquals($rawString, $this->encode($rawString));
    }

    public function testNullEncoder()
    {
        $this->setEncoder(null);

        $rawString = '{{ }}';
        $this->assertEquals($rawString, $this->encode($rawString));
    }
}
