<?php

namespace SwUrl\Tests;

use PHPUnit\Framework\TestCase;
use Swurl\Query;
use Swurl\Url;

class QueryTest extends TestCase
{
    public function testConstruct()
    {
        $q = new Query(['foo' => '']);
        $this->assertEquals('?foo=', $q->__toString());

        $q = new Query(['foo' => 'bar']);
        $this->assertEquals('?foo=bar', $q->__toString());

        $q = new Query('foo=bar&bar=baz');
        $this->assertEquals('?foo=bar&bar=baz', $q->__toString());

        $q = new Query('?foo=bar&bar=baz');
        $this->assertEquals('?foo=bar&bar=baz', $q->__toString());
    }

    public function testToString()
    {
        $query = new Query;
        $query['foo'] = 'bar';
        $this->assertEquals('?foo=bar', $query->__toString());

        $q = new Query;
        $this->assertEquals('', $q->__toString());
    }

    public function testMerge()
    {
        $query1 = new Query;
        $query1['foo'] = 'bar';
        $query1['bar'] = 'baz';

        $query2 = new Query;
        $query2['foo'] = 'bar1';

        $this->assertEquals('?foo=bar1&bar=baz', $query1->merge($query2)->__toString());
    }

    public function testEncoding()
    {
        $q = new Query;
        $q->set('foo', 'bar baz');
        $this->assertEquals('?foo=bar+baz', $q->__toString());

        $q->setEncoder('rawurlencode');
        $this->assertEquals('?foo=bar%20baz', $q->__toString());

        $complicated = '?focus=14&compare[]=77_14&compare[]=76_14&compare[]=12411_14&compare[]=4899_14';
        $q = new Query($complicated);
        $this->assertEquals($complicated, $q->__toString());

        $multiencoded = '?url=http%3A%2F%2Fwww.example.com%3Furl%3Dhttp%253A%252F%252Fwww.example.com';
        $q = new Query($multiencoded);
        $this->assertEquals('http://www.example.com?url=http%3A%2F%2Fwww.example.com', $q['url']);
        $this->assertEquals($multiencoded, (string) $q);
    }

    public function testNoticeOnNonExistentKey()
    {
        $q = new Query();
        $this->assertNull($q['p']);
    }

    public function testCorrectRebuildOfValidControlChars()
    {
        $testString = 'https://example.com?foo.bar=baz';
        $url = new Url($testString);
        $q = $url->getQuery();
        $this->assertArrayHasKey('foo.bar', $q);
        $this->assertEquals($url->__toString(), $testString);
    }

    public function testNaiveQueryParsingEncoding()
    {
        $testString = '?foo=bar%20baz';
        $q = new Query($testString);
        $this->assertArrayHasKey('foo', $q);
        $this->assertEquals('bar baz', $q['foo']);
    }

    public function testEmtpyQueryParamHandling()
    {
        $testString = '?foo=&bar';
        $testString2 = '?foo&bar=';

        $q = new Query($testString);
        $this->assertArrayHasKey('foo', $q);
        $this->assertEquals('', $q['foo']);

        $q = new Query($testString2);
        $this->assertArrayHasKey('foo', $q);
        $this->assertEquals('', $q['foo']);
    }

    public function testArrayParamHandling()
    {
        $testString = '?foos[bars][]=bar1&foos[bars][]=bar2';
        $q = new Query($testString);
        $this->assertCount(2, $q['foos']['bars']);
        $this->assertEquals($testString, (string) $q);
    }
}
