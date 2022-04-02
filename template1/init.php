<?php

$host = '0.0.0.0';
$port = '3307';
$dbname = 'arch';
$user = 'alex';
$password = 'docker';

function my_log(string $error_message, string $logger = null)
{
    if ($logger == 'security') {
        error_log($error_message . PHP_EOL, 3, './security.log');
    } else {    
        error_log($error_message . PHP_EOL, 3, './error.log');
    }
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

require_once '/home/alex/Documents/phpp/vendor/autoload.php';

function hometowns($dbh)
{
    $query = "select distinct name from hometowns;";

    $sth = $dbh->prepare($query);
    $sth->execute();
    $hometowns = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
    return $hometowns;
}

function departments($dbh)
{
    $query = "select distinct name from departments;";

    $sth = $dbh->prepare($query);
    $sth->execute();
    $departments = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
    return $departments;
}

$dbh = create_connection($host, $port, $dbname, $user, $password);
ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/sessions'));