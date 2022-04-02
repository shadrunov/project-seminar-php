<?php

require_once 'init.php';

// get student
function edit_student($dbh)
{
    
    session_start([
        'use_strict_mode' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
        'name' => 'securecookie'
    ]);

    if (!isset($_SESSION['is_auth'])) {
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

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        if (array_key_exists('ID', $_GET) and is_numeric($_GET['ID'])) {

            $query = "SELECT s.id as student_id,
            s.name       as student_name,
            s.hometown   as hometown_id,
            h.name       as hometown_name,
            s.department as department_id,
            d.name       as department_name
            from students s
                    left join hometowns h on s.hometown = h.id
                    left join departments d on s.department = d.id
            where s.id = :student_id;";
            $sth = $dbh->prepare($query);
            $sth->bindParam(':student_id', $_GET['ID']);
            $sth->execute();
            $old_params = $sth->fetchAll(PDO::FETCH_ASSOC);
            // print_r($old_params);
            if (sizeof($old_params) > 0) {
                echo $twig->render('edititem.html', [
                    'hometowns' => hometowns($dbh),
                    'departments' => departments($dbh),
                    'old_params' => $old_params[0],
                    'username' => $_SESSION['username']
                ]);
            } else {
                echo $twig->render('edititem.html', [
                    'hometowns' => hometowns($dbh),
                    'departments' => departments($dbh),
                    'error' => 'this record not found',
                    'username' => $_SESSION['username']
                ]);
            }

            return http_response_code(200);
        }
        print_r(json_encode(["status" => "error", "message" => "Failed to edit record"]));
        my_log("Error: can't edit, not all params recieved");
        return http_response_code(400);
    }

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        my_log("Error: expected POST, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }

    if (!(array_key_exists('student_name', $_POST) and
        array_key_exists('hometown_name', $_POST) and
        array_key_exists('department_name', $_POST) and
        array_key_exists('student_id', $_POST) and
        is_numeric($_POST['student_id'])
    )) {
        // print_r($_POST);
        print_r(json_encode(["status" => "error", "message" => "Failed to edit record"]));
        my_log("Error: can't edit, not all params recieved");
        return http_response_code(400);
    }

    $student_name = $_POST['student_name'];
    $student_id = $_POST['student_id'];
    $hometown_id = 0;
    $department_id = 0;
    $old_params = [
        'student_name' => $student_name,
        'hometown_name' => $_POST['hometown_name'],
        'department_name' => $_POST['department_name'],
        'student_id' => $_POST['student_id']
    ];

    // check if hometown is correct
    $query = "SELECT id from hometowns where name = :hometown;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':hometown', $_POST['hometown_name']);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) == 0) {
        echo $twig->render('edititem.html', [
            'old_params' => $old_params,
            'error' => 'hometown is incorrect',
            'hometowns' => hometowns($dbh),
            'departments' => departments($dbh),
            'username' => $_SESSION['username']
        ]);
        return http_response_code(400);
    }
    // my_log('116 new hometown id: ' . $res[0]['id'] . ' student_id ' . $student_id);
    $hometown_id = $res[0]['id'];

    // check if department is correct
    $query = "SELECT id from departments where name = :department;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':department', $_POST['department_name']);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) == 0) {
        echo $twig->render('edititem.html', [
            'old_params' => $old_params,
            'error' => 'department is incorrect',
            'hometowns' => hometowns($dbh),
            'departments' => departments($dbh),
            'username' => $_SESSION['username']
        ]);
        return http_response_code(400);
    }
    // my_log('135 new department id: ' . $res[0]['id'] . ' student_id ' . $student_id);
    $department_id = $res[0]['id'];

    // check if student ID is correct
    $query = "SELECT name, hometown, department from students where id = :id;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':id', $student_id);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) == 0) {
        echo $twig->render('edititem.html', [
            'old_params' => $old_params,
            'error' => 'record not found',
            'hometowns' => hometowns($dbh),
            'departments' => departments($dbh),
            'username' => $_SESSION['username']
        ]);
        return http_response_code(400);
    }

    // edit
    $new_student_name = $res[0]["name"];
    $new_hometown_id = $res[0]["hometown"];
    $new_department_id = $res[0]["department"];
    // my_log('values to insert before: ' . $new_student_name . " " . $new_hometown_id . " " . $new_department_id . " " . $student_id);
    if ($student_name != '')
        $new_student_name = $student_name;
    if ($hometown_id > 0)
        $new_hometown_id = $hometown_id;
    if ($department_id > 0)
        $new_department_id = $department_id;
    // my_log('values to insert after: ' . $new_student_name . " " . $new_hometown_id . " " . $new_department_id . " " . $student_id);


    $query = "UPDATE students
            set name = :name, 
            hometown = :hometown,
            department = :department
            where id = :id;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':name', $new_student_name);
    $sth->bindParam(':hometown', $new_hometown_id, PDO::PARAM_INT);
    $sth->bindParam(':department', $new_department_id, PDO::PARAM_INT);
    $sth->bindParam(':id', $student_id, PDO::PARAM_INT);
    $sth->execute();
    // echo $new_student_name;
    // echo $new_hometown_id;
    // echo $new_department_id;
    // echo $student_id;
    // my_log($new_student_name . " " . $new_hometown_id . " " . $new_department_id . " " . $student_id);
    header('Location: ' . 'getitem.php?ID=' . $student_id, true, 302);
}

edit_student($dbh);
$dbh = null;
