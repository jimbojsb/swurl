<?php
use Swurl\Query;

class QueryTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $q = new Query(["foo" => "bar"]);
        $this->assertEquals("?foo=bar", $q->__toString());

        $q = new Query("foo=bar&bar=baz");
        $this->assertEquals("?foo=bar&bar=baz", $q->__toString());

        $q = new Query("?foo=bar&bar=baz");
        $this->assertEquals("?foo=bar&bar=baz", $q->__toString());
    }

    public function testToString()
    {
        $query = new Query;
        $query["foo"] = "bar";
        $this->assertEquals("?foo=bar", $query->__toString());

        $q = new Query;
        $this->assertEquals('', $q->__toString());
    }

    public function testMerge()
    {
        $query1 = new Query;
        $query1["foo"] = "bar";
        $query1["bar"] = "baz";

        $query2 = new Query;
        $query2["foo"] = "bar1";

        $this->assertEquals("?foo=bar1&bar=baz", $query1->merge($query2)->__toString());
    }

    public function testEncoding()
    {
        $q = new Query;
        $q->set('foo', 'bar baz');
        $this->assertEquals('?foo=bar+baz', $q->__toString());

        $q->setEncoder('rawurlencode');
        $this->assertEquals('?foo=bar%20baz', $q->__toString());

        $complicated = '';
        $q = new Query($complicated);
        $this->assertEquals($complicated, $q->__toString());

    }
}