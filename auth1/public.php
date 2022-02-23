<?php

require_once 'init.php';

// public
function public_res()
{   
    if ($_SERVER['REQUEST_METHOD'] != 'GET') {
        my_log("Error: expected POST, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }

    
    echo "<h1>OK</h1>";
    echo "<p>you see a publicly accessible resourse! </p>";
    return http_response_code(200);
}

public_res($dbh);
$dbh = null;
