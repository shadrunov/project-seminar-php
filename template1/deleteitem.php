<?php

require_once 'init.php';

// delete student
function delete_student($dbh)
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

            $query = "SELECT id as student_id from students where id = :student_id;";
            $sth = $dbh->prepare($query);
            $sth->bindParam(':student_id', $_GET['ID']);
            $sth->execute();
            $students = $sth->fetchAll(PDO::FETCH_ASSOC);
            // print_r($students);
            if (sizeof($students) > 0) {
                echo $twig->render('deleteitem.html', [
                    'student' => $students[0],
                    'username' => $_SESSION['username']
                ]);
            } else {
                echo $twig->render('deleteitem.html', [
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

    if (!(array_key_exists('ID', $_POST) and is_numeric($_POST['ID']))) {
        print_r(json_encode([]));
        my_log("Error: can't delete, not all params recieved");
        return http_response_code(400);
    }

    $student_id = $_POST['ID'];

    // check if M-M doesn't exist
    $query = "SELECT id from courses_students where student = :student_id;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':student_id', $student_id);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) > 0) {
        print_r(json_encode(["status" => "error", "message" => "Failed to delete record"]));
        my_log("Error: link exists, can't delete");
        return http_response_code(400);
    }

    // check if record exist
    $query = "SELECT id from students where id = :student_id;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':student_id', $student_id);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) == 0) {
        print_r(json_encode(["status" => "error", "message" => "Failed to delete record"]));
        my_log("Error: invalid student id");
        return http_response_code(400);
    }

    $query = "DELETE 
            from students 
            where id = :student_id;";

    $sth = $dbh->prepare($query);
    $sth->bindParam(':student_id', $student_id);
    $sth->execute();

    header('Location: ' . 'listitems.php', true, 302);
}

delete_student($dbh);
$dbh = null;
