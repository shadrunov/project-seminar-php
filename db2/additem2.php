<?php

require_once 'init.php';

// get student
function add_course($dbh)
{
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        my_log("Error: expected POST, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }

    if (!(array_key_exists('name', $_POST) and is_string($_POST['name']) and
        array_key_exists('department', $_POST) and is_numeric($_POST['department']) and
        array_key_exists('rating', $_POST) and is_numeric($_POST['rating'])
    )) {
        print_r(json_encode(["status" => "error", "message" => "Failed to add record"]));
        my_log("Error: can't add, not all params recieved");
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
    $query = "INSERT into courses (name, department, rating)
                values (:name, :department, :rating);";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':name', $_POST['name']);
    $sth->bindParam(':department', $_POST['department'], PDO::PARAM_INT);
    $sth->bindParam(':rating', $_POST['rating'], PDO::PARAM_INT);
    $sth->execute();


    // find new entry
    $query = "SELECT id from courses
              where name = :name and 
              department = :department and
              rating = :rating;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':name', $_POST['name']);
    $sth->bindParam(':department', $_POST['department'], PDO::PARAM_INT);
    $sth->bindParam(':rating', $_POST['rating'], PDO::PARAM_INT);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    print_r(json_encode(
        ["status" => "success", "id" => end($res)["id"]]
    ));
}

add_course($dbh);
$dbh = null;
