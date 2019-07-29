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
     * @param $jwt
     *
     * @return \stdClass
     */
    public function decode($jwt): \stdClass;

    /**
     * @param $jwt
     *
     * @return string
     */
    public function sign($jwt): string;
}
