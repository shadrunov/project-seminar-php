<?php

$host = '0.0.0.0';
$port = '3307';
$dbname = 'arch';
$user = 'alex';
$password = 'docker';

function my_log(string $error_message)
{
    error_log($error_message . PHP_EOL, 3, './error.log');
}

function create_connection($host, $port, $dbname, $user, $password): PDO
{
    try {
        $dbh = new PDO('mysql:host=' . $host . ':' . $port . ';dbname=' . $dbname . ';charset=utf8', $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    } catch (PDOException $e) {
        my_log("Error: connection to DB failed: " . $e->getMessage());
        $dbh = null;
        print_r(json_encode([]));
        die(http_response_code(400));
    }
}




$dbh = create_connection($host, $port, $dbname, $user, $password);

