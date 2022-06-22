<?php

namespace myapp\Controller;

use myapp\Controller\DBClass;
use Exception;

class LoginClass extends DBClass
{
    public function router()
    {
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
        return $this->twig->render('login.html', []);
    }

    private function post()
    {
        if (!(array_key_exists('username', $_POST) and is_string($_POST['username']) and
            array_key_exists('password', $_POST) and is_string($_POST['password'])
        )) {
            http_response_code(400);
            $this->security_logger->logEvent("Login error: malformed request", __FILE__, __LINE__, __FUNCTION__);
            return $this->twig->render('login.html', [
                'error' => 'Failed to login: malformed request'
            ]);
        }

        $query = "SELECT pass_hash from users where username = :username;";
        $res = $this->queryFetchAll($query, [":username" => $_POST['username']]);

        if (count($res) != 1) {
            http_response_code(400);
            $this->lsecurity_loggerogger->logEvent("Login error: no such user", __FILE__, __LINE__, __FUNCTION__);
            return $this->twig->render('login.html', [
                'error' => "Failed to login: no such user"
            ]);
        }

        $hash = $res[0]['pass_hash'];
        $checked = password_verify($_POST['password'], $hash);

        if (!$checked) {
            http_response_code(200);
            $this->security_logger->logEvent("Login error: wrong password", __FILE__, __LINE__, __FUNCTION__);
            return $this->twig->render('login.html', [
                'error' => "Invalid credentials"
            ]);
        }

        session_start([
            'use_strict_mode' => true,
            'cookie_httponly' => true,
            'cookie_samesite' => 'Strict',
            'name' => 'securecookie'
        ]);

        $this->security_logger->logEvent("login: session started = " . session_id() . PHP_EOL, __FILE__, __LINE__, __FUNCTION__);
        session_regenerate_id($delete_old_session = true);
        $this->security_logger->logEvent("login: session regenerated = " . session_id() . PHP_EOL, __FILE__, __LINE__, __FUNCTION__);

        $_SESSION['is_auth'] = true;
        $_SESSION['username'] = $_POST['username'];

        $this->security_logger->logEvent("Login: successful", __FILE__, __LINE__, __FUNCTION__);
        return header('Location: ' . 'index.php?page=item', true, 302);
    }
}
