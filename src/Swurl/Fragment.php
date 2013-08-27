<?php
namespace Swurl;

class Fragment
{
    private $fragment;

    public function __construct($fragment)
    {
        $this->fragment = str_replace("#", "", $fragment);
    }

    public function __toString()
    {
        return "#$this->fragment";
    }
}