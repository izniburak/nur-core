<?php

namespace Nur\Auth\Jwt;

use DateTime;
use DomainException;
use Firebase\JWT\JWT as FirebaseJwt;
use Illuminate\Support\Str;
use UnexpectedValueException;

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
     * JWT Authenticated User
     *
     * @var null|\stdClass
     */
    protected $user = null;

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
     * @throws JwtException
     */
    public function decode($jwt): \stdClass
    {
        try {
            return FirebaseJwt::decode($jwt, $this->secret, [$this->algorithm]);
        } catch (UnexpectedValueException $e) {
            throw new JwtException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string $msg
     *
     * @return string
     * @throws DomainException
     */
    public function sign($msg): string
    {
        try {
            return FirebaseJwt::sign($msg, $this->secret, $this->algorithm);
        } catch (DomainException $e) {
            throw new JwtException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $user
     *
     * @return string
     */
    public function login($user): string
    {
        return $this->encode(['data' => $user]);
    }

    /**
     * @return bool|\stdClass
     * @throws JwtException
     */
    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }

        if ($token = request()->bearerToken()) {
            try {
                $user = $this->decode($token);
            } catch (JwtException $e) {
                throw new JwtException($e->getMessage(), $e->getCode());
            }

            $credential = json_decode(json_encode($user->data), true);
            if (auth()->validate($credential)) {
                return $this->user = auth()->user();
            }
        }

        throw new JwtException("JWT Token required");
    }
}
