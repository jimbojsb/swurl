<?php
namespace Swurl;

trait Encodeable
{
    private $encoder = 'urlencode';

    public function setEncoder($encoder)
    {
        $this->encoder = $encoder;
    }

    private function encode($string)
    {
        return call_user_func_array($this->encoder, [$string]);
    }
}