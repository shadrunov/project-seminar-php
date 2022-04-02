<?php

require_once 'init.php';

// login
function login($dbh)
{

    $loader = new \Twig\Loader\FilesystemLoader('./templates');
    $options = [
        'debug' => true, // включение/выключение отладки
        'cache' => false // включение/выключение кэширования
    ];
    $twig = new \Twig\Environment($loader, $options);

    // empty
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        echo $twig->render('login.html', [
        ]);
        return http_response_code(200);
    }

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        my_log("Error: expected POST, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }

    if (!(array_key_exists('username', $_POST) and is_string($_POST['username']) and
        array_key_exists('password', $_POST) and is_string($_POST['password'])
    )) {
        echo $twig->render('login.html', [
            'error' => 'Failed to login: malformed request'
        ]);
        my_log("Login error: malformed request", 'security');
        return http_response_code(400);
    }


    $query = "SELECT pass_hash from users where username = :username;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':username', $_POST['username']);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    if (count($res) != 1) {
        echo $twig->render('login.html', [
            'error' => "Failed to login: no such user"
        ]);
        my_log("Login error: no such user", 'security');
        return http_response_code(400);
    }

    $hash = $res[0]['pass_hash'];
    $checked = password_verify($_POST['password'], $hash);

    if (!$checked) {
        echo $twig->render('login.html', [
            'error' => "Invalid credentials"
        ]);
        my_log("Login error: wrong password", 'security');
        return http_response_code(200);
    }

    session_start([
        'use_strict_mode' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
        'name' => 'securecookie'
    ]);

    my_log("login: session started = " . session_id() . PHP_EOL, 'security');
    session_regenerate_id($delete_old_session = true);
    my_log("login: session regenerated = " . session_id() . PHP_EOL, 'security');

    $_SESSION['is_auth'] = true;
    $_SESSION['username'] = $_POST['username'];

    my_log("Login: successful", 'security');
    header('Location: ' . 'listitems.php', true, 302);
}

login($dbh);
$dbh = null;
