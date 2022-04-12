<?php

namespace Nur\Http;

class Session
{
    /**
     * Set session.
     *
     * @param array|string $key
     * @param mixed        $value
     *
     * @return void
     */
    public function set($key, $value = null): void
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $_SESSION[$k] = $v;
            }
        } else {
            $_SESSION[$key] = $value;
        }
    }

    /**
     * Get session method.
     *
     * @param string|null $key
     *
     * @return null|mixed
     */
    public function get(?string $key = null)
    {
        return is_null($key) ? $_SESSION : ($this->has($key) ? $_SESSION[$key] : null);
    }

    /**
     * Session has key ?
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Setting Flash Message
     *
     * @param string      $key
     * @param mixed       $value
     * @param string|null $redirect
     */
    public function setFlash(string $key, $value, string $redirect = null): void
    {
        $this->set('_nur_flash', [$key => $value]);

        if (!is_null($redirect)) {
            uri()->redirect($redirect);
        }
    }

    /**
     * Get flash message
     */
    public function getFlash(?string $key = null)
    {
        if (!is_null($key)) {
            $value = null;

            if ($this->hasFlash($key)) {
                $value = $this->get('_nur_flash')[$key];
                unset($_SESSION['_nur_flash'][$key]);
            }

            return $value;
        }

        return $key;
    }

    /**
     * Session has flash key ?
     */
    public function hasFlash(string $key): bool
    {
        return isset($_SESSION['_nur_flash'][$key]);
    }

    /**
     * Delete session method.
     */
    public function delete(string $key): void
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Delete all session method.
     */
    public function destroy(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Get Session ID
     */
    public function id(): string
    {
        return session_id();
    }
}
