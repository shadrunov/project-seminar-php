<?php

require_once 'init.php';

// private
function private_res()
{   
    if ($_SERVER['REQUEST_METHOD'] != 'GET') {
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
        my_log("Access error: attempt of unauthorised access");
        echo "<h1>Forbidden</h1>";
        return http_response_code(403);
    }

    echo "<h1>OK</h1>";
    echo "<p>you see a private resourse! </p>";
    return http_response_code(200);
}

private_res($dbh);
$dbh = null;
