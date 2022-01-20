<?php

require_once 'init.php';

// delete student
function delete_student($dbh)
{
    if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
        my_log("Error: expected DELETE, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }

    if (!(array_key_exists('ID', $_GET) and is_numeric($_GET['ID']))) {
        print_r(json_encode([]));
        my_log("Error: can't delete, not all params recieved");
        return http_response_code(400);
    }

    $student_id = $_GET['ID'];

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
    print_r(json_encode(["status" => "success"]));
}

delete_student($dbh);
$dbh = null;
