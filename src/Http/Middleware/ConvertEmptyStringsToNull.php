<?php

namespace Nur\Http\Middleware;

class ConvertEmptyStringsToNull extends TransformsRequest
{
    protected function transform(string $key, mixed $value): mixed
    {
        return $value === '' ? null : $value;
    }
}