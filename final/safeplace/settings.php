<?php

require_once '/home/alex/Documents/phpp/vendor/autoload.php';

define('DEBUG', false);
define('MODULES_DIR', '../safeplace/');

$host = '0.0.0.0';
$port = '3307';
$dbname = 'arch';
$user = 'alex';
$password = 'docker';

$dbSettings = [
    'connectionString' => 'mysql:host=' . $host . ':' . $port . ';dbname=' . $dbname . ';charset=utf8',
    'dbUser' => $user,
    'dbPwd' => $password
];

ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/sessions'));

$loader = new \Twig\Loader\FilesystemLoader('../safeplace/Templates');
$options = [
    'debug' => true, // включение/выключение отладки
    'cache' => false // включение/выключение кэширования
];
$twig = new \Twig\Environment($loader, $options);
