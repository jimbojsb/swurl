<?php
namespace Swurl;

class Fragment
{
    private $fragment;

    public function __construct($fragment = null)
    {
        $this->fragment = str_replace("#", "", $fragment);
    }

    public function __toString()
    {
        if ($this->fragment) {
            return "#$this->fragment";
        }
        return '';
    }
}