<?php

namespace Nur\Auth;

use Symfony\Component\HttpFoundation\Response;

class Auth
{
    /**
     * @var
     */
    private $user = null;

    /**
     * @var string
     */
    private $sessionId = '_auth_user_id';

    /**
     * @var mixed|null
     */
    private $model = null;

    /**
     * @var string
     */
    private $primaryKey;

    /**
     * @var mixed|\Nur\Http\Session
     */
    private $session;

    /**
     * Auth constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->session = session();
        $this->checkAuthProvider();

        if ($id = $this->id()) {
            $this->loginUsingId($id);
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
    public function attempt(array $credentials, $remember = false)
    {
        if ($this->validate($credentials)) {
            return $this->login($this->user, $remember);
        }

        return false;
    }

    /**
     * Login the user with the given user
     *
     * @param \Nur\Database\Model $user
     * @param bool                $remember
     *
     * @return bool
     */
    public function login($user, $remember = false)
    {
        if ($user) {
            $this->session->set($this->sessionId, $user->{$this->primaryKey});
            return true;
        }

        return false;
    }

    /**
     * Login the user with the given id
     *
     * @param      $id
     * @param bool $remember
     *
     * @return bool
     */
    public function loginUsingId($id, $remember = false)
    {
        return $this->attempt([$this->primaryKey => $id], $remember);
    }

    /**
     * Attempts to log a user in with the given credentials
     *
     * @param array $credentials
     *
     * @return bool
     */
    public function validate(array $credentials)
    {
        if ($user = $this->model->where($credentials)->first()) {
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
    public function logout()
    {
        $this->session->delete($this->sessionId);
    }

    /**
     * Checks if a user is currently logged in
     *
     * @return bool
     */
    public function check()
    {
        return $this->session->has($this->sessionId);
    }

    /**
     * Checks if the current user is a guest
     *
     * @return bool
     */
    public function guest()
    {
        return ! $this->check();
    }

    /**
     * Get the currently authenticated user
     *
     * @return bool
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * Get the currently authenticated user's ID
     *
     * @return bool
     */
    public function id()
    {
        return $this->check() ? $this->session->get($this->sessionId) : null;
    }

    /**
     * Get JWT Instance
     *
     * @return Jwt\Jwt
     */
    public function jwt()
    {
        return resolve('jwt');
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
        if (! $isAuthentication) {
            response()->setStatusCode(Response::HTTP_UNAUTHORIZED)
                ->header('WWW-Authenticate', 'Basic realm="Access denied"')
                ->send();
            exit;
        }

        return true;
    }

    /**
     * Check Auth Provider
     *
     * @return void
     */
    private function checkAuthProvider()
    {
        $auth = config('auth');
        if ($auth['driver'] === 'eloquent') {
            $this->model = app($auth['model']);
            $this->primaryKey = $this->model->getKeyName();
            return;
        }

        $this->model = app(\Nur\Database\Builder::class)->table($auth['table']);
        $this->primaryKey = $auth['primary_key'];
    }
}
