<?php

namespace Nur\Auth\Jwt;

interface JwtInterface
{
    /**
     * @param array $payload
     * @param null  $keyId
     * @param null  $head
     *
     * @return string
     */
    public function encode(array $payload, $keyId = null, $head = null): string;

    /**
     * @param string $jwt
     *
     * @return \stdClass
     */
    public function decode(string $jwt): \stdClass;

    /**
     * @param $value
     *
     * @return string
     */
    public function sign(string $value): string;
}
