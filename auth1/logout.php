<?php

require_once 'init.php';

// login
function logout($dbh)
{   
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        my_log("Error: expected POST, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }

    session_start([
        'use_strict_mode' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
        'name' => 'securecookie'
    ]);

    if (! isset($_SESSION['is_auth'])) {
        session_destroy();
        my_log("Logout error: not authorised");
        print_r(json_encode(["status" => "error", "message" => "Failed to logout: not authorised"]));
        return http_response_code(400);
    }

    session_destroy();
    my_log("Logout: successful, session destroyed: " . session_id() . PHP_EOL);
    echo "<h1>OK</h1>";
    return http_response_code(200);
}

logout($dbh);
$dbh = null;
