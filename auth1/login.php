<?php

require_once 'init.php';

// login
function login($dbh)
{

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        my_log("Error: expected POST, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }

    if (!(array_key_exists('username', $_POST) and is_string($_POST['username']) and
        array_key_exists('password', $_POST) and is_string($_POST['password'])
    )) {
        print_r(json_encode(["status" => "error", "message" => "Failed to login: malformed request"]));
        my_log("Login error: malformed request");
        return http_response_code(400);
    }


    $query = "SELECT pass_hash from users where username = :username;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':username', $_POST['username']);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    if (count($res) != 1) {
        print_r(json_encode(["status" => "error", "message" => "Failed to login: no such user"]));
        my_log("Login error: no such user");
        return http_response_code(400);
    }

    $hash = $res[0]['pass_hash'];
    $checked = password_verify($_POST['password'], $hash);

    if (!$checked) {
        echo "<h1>Invalid credentials</h1>";
        my_log("Login error: wrong password");
        return http_response_code(200);
    }

    session_start([
        'use_strict_mode' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
        'name' => 'securecookie'
    ]);

    my_log("login: session started = " . session_id() . PHP_EOL);
    session_regenerate_id($delete_old_session = true);
    my_log("login: session regenerated = " . session_id() . PHP_EOL);

    $_SESSION['is_auth'] = true;

    echo "<h1>OK</h1>";
    my_log("Login: successful");
    return http_response_code(200);
}

login($dbh);
$dbh = null;
