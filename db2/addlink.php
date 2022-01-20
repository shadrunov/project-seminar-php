<?php

require_once 'init.php';

// add link
function add_link($dbh)
{
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        my_log("Error: expected POST, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }

    if (!(array_key_exists('ID1', $_POST) and is_numeric($_POST['ID1']) and
        array_key_exists('ID2', $_POST) and is_numeric($_POST['ID2'])
    )) {
        print_r(json_encode(["status" => "error", "message" => "Failed to add record"]));
        my_log("Error: can't add, not all params recieved");
        return http_response_code(400);
    }


    // check if student is correct
    $query = "SELECT id from students where id = :student;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':student', $_POST['ID1']);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) == 0) {
        print_r(json_encode(["status" => "error", "message" => "Failed to add record"]));
        my_log("Error: invalid student id");
        return http_response_code(400);
    }

    // check if course is correct
    $query = "SELECT id from courses where id = :course;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':course', $_POST['ID2']);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) == 0) {
        print_r(json_encode(["status" => "error", "message" => "Failed to add record"]));
        my_log("Error: invalid course id");
        return http_response_code(400);
    }

    // check if same link not created
    $query = "SELECT id 
            from courses_students 
            where student = :student 
            and course = :course;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':course', $_POST['ID1'], PDO::PARAM_INT);
    $sth->bindParam(':student', $_POST['ID2'], PDO::PARAM_INT);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) > 0) {
        print_r(json_encode(["status" => "error", "message" => "Failed to add record"]));
        my_log("Error: link already exists");
        return http_response_code(400);
    }

    // insert
    $query = "INSERT into courses_students (course, student)
                values (:course, :student);";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':course', $_POST['ID1'], PDO::PARAM_INT);
    $sth->bindParam(':student', $_POST['ID2'], PDO::PARAM_INT);
    $sth->execute();

    print_r(json_encode(
        ["status" => "success"]
    ));
}

add_link($dbh);
$dbh = null;
