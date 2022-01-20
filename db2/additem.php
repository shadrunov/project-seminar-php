<?php

require_once 'init.php';

// get student
function add_student($dbh)
{
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        my_log("Error: expected POST, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }

    if (!(array_key_exists('name', $_POST) and is_string($_POST['name']) and
        array_key_exists('hometown', $_POST) and is_numeric($_POST['hometown']) and
        array_key_exists('department', $_POST) and is_numeric($_POST['department'])
    )) {
        print_r(json_encode(["status" => "error", "message" => "Failed to add record"]));
        my_log("Error: can't add, not all params recieved");
        return http_response_code(400);
    }


    // check if hometown is correct
    $query = "SELECT id from hometowns where id = :hometown;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':hometown', $_POST['hometown']);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) == 0) {
        print_r(json_encode(["status" => "error", "message" => "Failed to add record"]));
        my_log("Error: invalid hometown id");
        return http_response_code(400);
    }

    // check if department is correct
    $query = "SELECT id from departments where id = :department;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':department', $_POST['department']);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) == 0) {
        print_r(json_encode(["status" => "error", "message" => "Failed to add record"]));
        my_log("Error: invalid department id");
        return http_response_code(400);
    }

    // insert
    $query = "INSERT into students (name, hometown, department)
                values (:name, :hometown, :department);";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':name', $_POST['name']);
    $sth->bindParam(':hometown', $_POST['hometown'], PDO::PARAM_INT);
    $sth->bindParam(':department', $_POST['department'], PDO::PARAM_INT);
    $sth->execute();


    // find new entry
    $query = "SELECT id from students
              where name = :name and 
              hometown = :hometown and 
              department = :department;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':name', $_POST['name']);
    $sth->bindParam(':hometown', $_POST['hometown'], PDO::PARAM_INT);
    $sth->bindParam(':department', $_POST['department'], PDO::PARAM_INT);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    print_r(json_encode(
        ["status" => "success", "id" => end($res)["id"]]
    ));
}

add_student($dbh);
$dbh = null;
