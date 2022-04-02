<?php

require_once 'init.php';

// get student
function add_student($dbh)
{

    session_start([
        'use_strict_mode' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
        'name' => 'securecookie'
    ]);
    
    if (!(isset($_SESSION['is_auth']) and $_SESSION['is_auth'] == 1)) {
        my_log("Access error: attempt of unauthorised access", 'security');
        header('Location: ' . 'login.php', true, 302);
        return http_response_code(302);
    }

    $loader = new \Twig\Loader\FilesystemLoader('./templates');
    $options = [
        'debug' => true, // включение/выключение отладки
        'cache' => false // включение/выключение кэширования
    ];
    $twig = new \Twig\Environment($loader, $options);

    // empty
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        echo $twig->render('additem.html', [
            'hometowns' => hometowns($dbh),
            'departments' => departments($dbh),
            'username' => $_SESSION['username']
        ]);
        return http_response_code(200);
    }

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        my_log("Error: expected POST, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }

    if (!(array_key_exists('student_name', $_POST) and is_string($_POST['student_name']) and
        array_key_exists('hometown_name', $_POST) and
        array_key_exists('department_name', $_POST)
    )) {
        print_r(json_encode(["status" => "error", "message" => "Failed to add record"]));
        my_log("Error: can't add, not all params recieved");
        return http_response_code(400);
    }


    $student_name = $_POST['student_name'];
    $hometown_id = 0;
    $department_id = 0;
    $old_params = [
        'student_name' => $student_name,
        'hometown_name' => $_POST['hometown_name'],
        'department_name' => $_POST['department_name']
    ];

    // check if hometown is correct
    $query = "SELECT id from hometowns where name = :hometown;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':hometown', $_POST['hometown_name']);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) == 0) {
        echo $twig->render('additem.html', [
            'old_params' => $old_params,
            'error' => 'hometown is incorrect',
            'hometowns' => hometowns($dbh),
            'departments' => departments($dbh),
            'username' => $_SESSION['username']
        ]);
        return http_response_code(400);
    }
    $hometown_id = $res[0]['id'];


    // check if department is correct
    $query = "SELECT id from departments where name = :department;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':department', $_POST['department_name']);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) == 0) {
        echo $twig->render('additem.html', [
            'old_params' => $old_params,
            'error' => 'department is incorrect',
            'hometowns' => hometowns($dbh),
            'departments' => departments($dbh),
            'username' => $_SESSION['username']
        ]);
        return http_response_code(400);
    }
    $department_id = $res[0]['id'];


    // insert
    $query = "INSERT into students (name, hometown, department)
                values (:name, :hometown, :department);";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':name', $student_name);
    $sth->bindParam(':hometown', $hometown_id, PDO::PARAM_INT);
    $sth->bindParam(':department', $department_id, PDO::PARAM_INT);
    $sth->execute();


    // find new entry
    $query = "SELECT id from students
              where name = :name and 
              hometown = :hometown and 
              department = :department;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':name', $student_name);
    $sth->bindParam(':hometown', $hometown_id, PDO::PARAM_INT);
    $sth->bindParam(':department', $department_id, PDO::PARAM_INT);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    header('Location: ' . 'getitem.php?ID=' . end($res)["id"], true, 302);
}

add_student($dbh);
$dbh = null;
