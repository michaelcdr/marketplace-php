<?php

namespace services;

class SessionService
{
    public function start()
    {
        session_start();
    }

    public function isSessionStarted()
    {
        if (php_sapi_name() !== 'cli') {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }

    public function isAuthorized(): bool
    {
        if (isset($_SESSION["userId"])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
