<?php

namespace Nur\Http;

class Cookie
{
    /**
     * Set cookie method.
     *
     * @param array|string $key
     * @param string       $value
     * @param integer      $time
     *
     * @return void
     */
    public function set($key, $value = null, $time = 0): void
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                setcookie($k, $v, ($time == 0 ? 0 : time() + $time), '/');
                $_COOKIE[$k] = $v;
            }
        } else {
            setcookie($key, $value, ($time == 0 ? 0 : time() + $time), '/');
            $_COOKIE[$key] = $value;
        }
    }

    /**
     * Get cookie method.
     *
     * @param string|null $key
     *
     * @return null|mixed
     */
    public function get(string $key = null)
    {
        return is_null($key) ? $_COOKIE : ($this->has($key) ? $_COOKIE[$key] : null);
    }

    /**
     * Cookie has key ?
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($_COOKIE[$key]);
    }

    /**
     * Delete cookie method.
     *
     * @param string $key
     *
     * @return void
     */
    public function delete(string $key): void
    {
        if ($this->has($key)) {
            setcookie($key, null, -1, '/');
            unset($_COOKIE[$key]);
        }
    }

    /**
     * Delete all cookie method.
     *
     * @return void
     */
    public function destroy(): void
    {
        foreach ($_COOKIE as $key => $value) {
            setcookie($key, null, -1, '/');
            unset($_COOKIE[$key]);
        }
    }
}
