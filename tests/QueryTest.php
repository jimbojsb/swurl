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
}