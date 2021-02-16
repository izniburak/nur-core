<?php

namespace Nur\Auth\Jwt;

use DateTime;
use DomainException;
use Firebase\JWT\JWT as FirebaseJwt;
use Illuminate\Support\Str;
use Nur\Auth\Auth;
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
     * JWT time to live for Refresh token
     *
     * @var int
     */
    protected $refreshTtl = 60 * 24 * 30;

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
     * @var Auth
     */
    protected $auth;

    /**
     * Jwt constructor.
     *
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;

        $jwt = config('auth.jwt');
        $this->secret = $jwt['secret'];
        $this->leeway = $jwt['leeway'];
        $this->algorithm = $jwt['alg'];
        $this->ttl = $jwt['ttl'];
        $this->refreshTtl = $jwt['refresh_ttl'];

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
     * @param string $jwt
     *
     * @return \stdClass
     * @throws JwtException
     */
    public function decode(string $jwt): \stdClass
    {
        try {
            return FirebaseJwt::decode($jwt, $this->secret, [$this->algorithm]);
        } catch (UnexpectedValueException $e) {
            throw new JwtException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string $value
     *
     * @return string
     * @throws DomainException
     */
    public function sign(string $value): string
    {
        try {
            return FirebaseJwt::sign($value, $this->secret, $this->algorithm);
        } catch (DomainException $e) {
            throw new JwtException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param array $credentials
     *
     * @return string
     */
    public function attempt(array $credentials): string
    {
        if ($this->auth->validate($credentials)) {
            return $this->login($this->auth->user()->getJWTClaims());
        }

        throw new JwtException('Invalid Credentials');
    }

    /**
     * @param array|object $credentials
     *
     * @return string
     */
    public function login($credentials): string
    {
        return $this->encode(['data' => $credentials]);
    }

    /**
     * @param object $user
     *
     * @return string
     */
    public function loginWithUser($user): string
    {
        return $this->login($user->getJWTClaims());
    }

    /**
     * Check JWT Token and Validate User
     *
     * @return bool|\stdClass
     * @throws JwtException
     */
    public function check()
    {
        if ($this->user !== null) {
            return true;
        }

        if ($token = request()->bearerToken()) {
            try {
                $user = $this->decode($token);
            } catch (JwtException $e) {
                throw new JwtException($e->getMessage(), $e->getCode());
            }

            $credentials = json_decode(json_encode($user->data), true);
            if ($this->auth->validate($credentials)) {
                $this->user = $this->auth->user();
                return true;
            }
        }

        throw new JwtException("JWT Token required");
    }

    /**
     * @param string $token JWT Token
     *
     * @return string
     */
    public function refresh(string $token): string
    {
        $decoded = $this->decode($token);
        return $this->encode(array_merge([], [
            'exp' => now()->addMinutes($this->refreshTtl)->getTimestamp(),
            'data' => $decoded->data,
        ]));
    }

    /**
     * Get JWT Authenticated User
     *
     * @return \stdClass|null
     */
    public function user()
    {
        return $this->user;
    }
}
