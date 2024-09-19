<?php

namespace Swurl;

use InvalidArgumentException;

trait Encodeable
{
    private $encoder = 'urlencode';

    public function setEncoder(string|false|null $encoder): void
    {
        if ($encoder && ! is_callable($encoder)) {
            throw new InvalidArgumentException('$encoder must be a callable function');
        }

        $this->encoder = $encoder ?? null;
    }

    private function isEncoded(?string $string): bool
    {
        if ($this->encoder) {
            $decoderFunction = str_replace('encode', 'decode', $this->encoder);
            if (call_user_func_array($decoderFunction, [$string]) != $string) {
                return true;
            }
        }

        return false;
    }

    private function encode(?string $string, bool $checkIfEncoded = true): string
    {
        if ($checkIfEncoded && $this->isEncoded($string)) {
            return $string;
        }
        if ($this->encoder) {
            return call_user_func_array($this->encoder, [$string]);
        }

        return $string;
    }
}
