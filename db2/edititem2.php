<?php

require_once 'init.php';

// edit course
function edit_course($dbh)
{
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        my_log("Error: expected POST, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }

    if (!((array_key_exists('name', $_POST) and is_string($_POST['name']) or
        array_key_exists('rating', $_POST) and is_numeric($_POST['rating']) or
        array_key_exists('department', $_POST) and is_numeric($_POST['department']))
        and
        array_key_exists('ID', $_POST) and is_numeric($_POST['ID'])
    )) {
        print_r(json_encode(["status" => "error", "message" => "Failed to edit record"]));
        my_log("Error: can't edit, not all params recieved");
        return http_response_code(400);
    }

    if (array_key_exists('department', $_POST)) {
        // check if department is correct
        $query = "SELECT id from departments where id = :department;";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':department', $_POST['department']);
        $sth->execute();
        $res = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (count($res) == 0) {
            print_r(json_encode(["status" => "error", "message" => "Failed to edit record"]));
            my_log("Error: invalid department id");
            return http_response_code(400);
        }
    }

    // check if course ID is correct
    $query = "SELECT name, department, rating from courses where id = :id;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':id', $_POST['ID']);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) == 0) {
        print_r(json_encode(["status" => "error", "message" => "Failed to edit record"]));
        my_log("Error: invalid course id");
        return http_response_code(400);
    }

    // edit
    $name = $res[0]["name"];
    $rating = $res[0]["rating"];
    $department = $res[0]["department"];
    if (array_key_exists('name', $_POST))
        $name = $_POST['name'];
    if (array_key_exists('rating', $_POST))
        $rating = $_POST['rating'];
    if (array_key_exists('department', $_POST))
        $department = $_POST['department'];


    $query = "UPDATE courses
            set name = :name, 
            rating = :rating,
            department = :department
            where id = :id;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':name', $name);
    $sth->bindParam(':rating', $rating, PDO::PARAM_INT);
    $sth->bindParam(':department', $department, PDO::PARAM_INT);
    $sth->bindParam(':id', $_POST['ID'], PDO::PARAM_INT);
    $sth->execute();


    print_r(json_encode(
        ["status" => "success", "id" => $_POST['ID']]
    ));
}

edit_course($dbh);
$dbh = null;