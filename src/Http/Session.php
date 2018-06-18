<?php

namespace Nur\Http;

use Nur\Facades\Uri;

class Session
{
    /**
     * Set session method.
     * 
     * @param string $key
     * @param string $value
     * @return void
     */
    public function set($key, $value)
    {
        if(is_array($key)) {
            foreach ($key as $k => $v) {
                $_SESSION[$k] = $v;
            }
        }
        else {
            $_SESSION[$key] = $value;
        }

        return;
    }

    /**
     * Get session method.
     * 
     * @param string $key
     * @return null|mixed
     */
    public function get($key = null)
    {
        return (is_null($key) ? $_SESSION : ($this->hasKey($key) ? $_SESSION[$key] : null));
    }

    /**
     * Session has key ?
     * 
     * @param string $key
     * @return bool
     */
    public function hasKey($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Setting Flash Message
     * 
     * @param string $key
     * @param string $value
     * @param string $redirect
     * @return bool
     */
    public function setFlash($key, $value, $redirect = null)
    {
        $this->set('_nur_flash', [$key => $value]);
        if (!is_null($redirect)) {
            Uri::redirect($redirect);
        }

        return false;
    }

    /**
     * Getting Flash Message
     * 
     * @param string $key
     * @return null|string
     */
    public function getFlash($key = null)
    {
        if(!is_null($key)) {
            $value = null;
            if($this->hasFlash($key)) {
                $value = $this->get('_nur_flash')[$key];
                unset($_SESSION['_nur_flash'][$key]);
            }

            return $value;
        }

        return $key;
    }

    /**
     * Session has flash key ?
     * 
     * @param string $key
     * @return bool
     */
    public function hasFlash($key)
    {
        return isset($_SESSION['_nur_flash'][$key]);
    }

    /**
     * Delete session method.
     * 
     * @param string $key
     * @return void
     */
    public function delete($key)
    {
        if($this->hasKey($key)) {
            unset($_SESSION[$key]);
        }

        return;
    }

    /**
     * Delete all session method.
     *
     * @return void
     */
    public function destroy()
    {
        $_SESSION = [];
        session_destroy();
        return;
    }

    /**
     * Get Session ID
     *
     * @return string
     */
    public function id()
    {
        return session_id();
    }
}
