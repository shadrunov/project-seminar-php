<?php

namespace myapp\Controller;

use myapp\Controller\DBClass;
use Exception;

class LogoutClass extends DBClass
{
    public function router()
    {
        session_start([
            'use_strict_mode' => true,
            'cookie_httponly' => true,
            'cookie_samesite' => 'Strict',
            'name' => 'securecookie'
        ]);

        if (!isset($_SESSION['is_auth'])) {
            $this->logger->logEvent("Logout error: not authorised" . 'security', __FILE__, __LINE__, __FUNCTION__);
            header('Location: ' . 'index.php?page=login', true, 302);
            return http_response_code(302);
        }

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                return $this->get();
                break;
            case 'POST':
                return $this->post();
                break;
            default:
                throw new Exception("Error: expected GET or POST, not " . $_SERVER['REQUEST_METHOD']);
                break;
        }
    }

    private function get()
    {
        return $this->twig->render('logout.html', [
            'username' => $_SESSION['username']
        ]);
    }

    private function post()
    {
        session_destroy();
        $this->security_logger->logEvent("Logout: successful, session destroyed: " . session_id() . 'security', __FILE__, __LINE__, __FUNCTION__);
        return "<h1>OK</h1>";
    }
}
