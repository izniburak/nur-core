<?php

namespace Nur\Auth\Jwt;

use DateTime;
use Firebase\JWT\JWT as FirebaseJwt;
use Illuminate\Support\Str;

class Jwt implements JwtInterface
{
    /**
     * JWT Algorithm
     *
     * @var string
     */
    protected $algorithm = '';

    /**
     * JWT time to live
     *
     * @var int
     */
    protected $ttl = 60;

    /**
     * JWT Secret
     *
     * @var string
     */
    protected $secret = '';

    /**
     * @var int
     */
    protected $leeway = 0;

    /**
     * Jwt constructor.
     */
    public function __construct()
    {
        $jwt = config('auth.jwt');
        $this->secret = $jwt['secret'];
        $this->leeway = $jwt['leeway'];
        $this->algorithm = $jwt['alg'];
        $this->ttl = $jwt['ttl'];

        FirebaseJwt::$leeway = $this->leeway;
    }

    /**
     * @param array      $payload
     * @param mixed|null $keyId
     * @param array|null $head
     *
     * @return string
     * @throws
     */
    public function encode(array $payload, $keyId = null, $head = null): string
    {
        $now = new DateTime;
        $requiredClaims = [
            "jti" => Str::uuid(),
            "iss" => request()->getHttpHost(),
            "iat" => $now->getTimestamp(),
            "nbf" => $now->getTimestamp(),
            "exp" => $now->modify("+{$this->ttl} minutes")->getTimestamp(),
        ];
        $payload = array_merge($requiredClaims, $payload);

        return FirebaseJwt::encode($payload, $this->secret, $this->algorithm, $keyId, $head);
    }

    /**
     * @param $jwt
     *
     * @return \stdClass
     */
    public function decode($jwt): \stdClass
    {
        return FirebaseJwt::decode($jwt, $this->secret, [$this->algorithm]);
    }

    /**
     * @param string $msg
     *
     * @return string
     */
    public function sign($msg): string
    {
        return FirebaseJwt::sign($msg, $this->secret, $this->algorithm);
    }
}
