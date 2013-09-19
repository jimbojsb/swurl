<?php
namespace Swurl;

trait Encodeable
{
    private $encoder = 'urlencode';

    public function setEncoder($encoder)
    {
        $this->encoder = $encoder;
    }

    private function isEncoded($string)
    {
        $decoderFunction = str_replace('encode', 'decode', $this->encoder);
        if (call_user_func_array($decoderFunction, [$string]) != $string) {
            return true;
        }
        return false;
    }

    private function encode($string)
    {
        if ($this->isEncoded($string)) {
            return $string;
        }
        return call_user_func_array($this->encoder, [$string]);
    }
}