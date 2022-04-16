<?php

namespace Nur\Auth;

use Nur\Auth\Jwt\Jwt;
use Nur\Database\Model;
use Nur\Http\Response;

class Auth
{
    /**
     * @var
     */
    private $user = null;

    /**
     * @var string
     */
    private $sessionId = '_auth_id';

    /**
     * @var mixed|null
     */
    private $model = null;

    /**
     * @var string
     */
    private $primaryKey;

    /**
     * @var string
     */
    private $passwordField;

    /**
     * Auth constructor.
     *
     * @param string|null $prefix
     */
    public function __construct(string $prefix = null)
    {
        $this->checkAuthProvider();
        $this->setSessionPrefix($prefix);

        if ($id = $this->id()) {
            $this->loginById($id);
        }
    }

    /**
     * Attempts to log a user in with the given credentials
     *
     * @param array $credentials
     * @param bool  $remember
     *
     * @return bool
     */
    public function attempt(array $credentials, $remember = false): bool
    {
        if ($this->validate($credentials)) {
            return $this->login($this->user, $remember);
        }

        return false;
    }

    /**
     * Login the user with the given user
     *
     * @param Model $user
     * @param bool  $remember
     *
     * @return bool
     */
    public function login(Model $user, bool $remember = false): bool
    {
        if ($user) {
            session()->set($this->sessionId, $user->{$this->primaryKey});
            return true;
        }

        return false;
    }

    /**
     * Login the user with the given id
     *
     * @param       $id
     * @param array $where
     * @param bool  $remember
     *
     * @return bool
     */
    public function loginById($id, array $where = [], bool $remember = false): bool
    {
        if ($user = $this->model->where(array_merge([$this->primaryKey => $id], $where))->first()) {
            $this->user = $user;
            return $this->login($this->user, $remember);
        }

        return false;
    }

    /**
     * Attempts to log a user in with the given credentials
     *
     * @param array $credentials
     *
     * @return bool
     */
    public function validate(array $credentials): bool
    {
        $password = $credentials[$this->passwordField] ?? null;
        unset($credentials[$this->passwordField]);
        if ($user = $this->model->where($credentials)->first()) {
            if ($password && !hasher()->check($password, $user->{$this->passwordField})) {
                return false;
            }

            $this->user = $user;
            return true;
        }

        return false;
    }

    /**
     * Log out the current user
     *
     * @return void
     */
    public function logout(): void
    {
        $this->user = null;
        session()->delete($this->sessionId);
    }

    /**
     * Checks if a user is currently logged in
     *
     * @return bool
     */
    public function check(): bool
    {
        return session()->has($this->sessionId);
    }

    /**
     * Checks if the current user is a guest
     *
     * @return bool
     */
    public function guest(): bool
    {
        return !$this->check();
    }

    /**
     * Get the currently authenticated user
     *
     * @return mixed
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * Get the currently authenticated user's ID
     *
     * @return mixed
     */
    public function id()
    {
        return $this->check() ? session()->get($this->sessionId) : null;
    }

    /**
     * Get JWT Instance
     *
     * @return Jwt
     * @throws
     */
    public function jwt(): Jwt
    {
        return app()->make(Jwt::class);
    }

    /**
     * Basic Authentication
     *
     * @param array $credentials
     *
     * @return bool
     */
    public function basicAuth(array $credentials = [])
    {
        $basicAuthConfig = config('auth.basic');
        $useDatabase = $basicAuthConfig['driver'] === 'database';
        if (empty($credentials)) {
            $credentials = $basicAuthConfig['credentials'];
        } elseif (count($credentials) !== 2) {
            response()->setStatusCode(Response::HTTP_UNAUTHORIZED)->send();
            exit;
        }

        $credentials = array_values($credentials);
        $authUser = request()->header('PHP_AUTH_USER');
        $authPass = request()->header('PHP_AUTH_PW');
        $isAuthentication = ($useDatabase && $this->validate(array_combine($credentials, [$authUser, $authPass]))) ||
            ($authUser == $credentials[0] && $authPass == $credentials[1]);
        if (!$isAuthentication) {
            response()->setStatusCode(Response::HTTP_UNAUTHORIZED)
                ->header('WWW-Authenticate', 'Basic realm="Access denied"')
                ->send();
            exit;
        }

        return true;
    }

    /**
     * Add prefix to Auth Session Id
     *
     * @param string $prefix
     */
    protected function setSessionPrefix(?string $prefix): void
    {
        $key = substr(app()->key(), 0, 3);
        $this->sessionId = "_{$prefix}{$this->sessionId}_{$key}";
    }

    /**
     * Check Auth Provider
     *
     * @return void
     */
    private function checkAuthProvider(): void
    {
        $auth = config('auth');
        $this->passwordField = $auth['password_field'] ?? 'password';
        if ($auth['driver'] === 'eloquent') {
            $this->model = app($auth['model']);
            $this->primaryKey = $this->model->getKeyName();
            return;
        }

        $this->model = app('builder')->table($auth['table']);
        $this->primaryKey = $auth['primary_key'];
    }
}
