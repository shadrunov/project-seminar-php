<?php

require_once 'init.php';

// get student
function get_student($dbh)
{
    if ($_SERVER['REQUEST_METHOD'] != 'GET') {
        my_log("Error: expected GET, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }

    if (!(array_key_exists('ID', $_GET) and is_numeric($_GET['ID']))) {
        print_r(json_encode([]));
        my_log("Error: can't edit, not all params recieved");
        return http_response_code(400);
    }

    $student_id = $_GET['ID'];

    $query = "SELECT s.id,
            s.name,
            s.hometown   as hometown_id,
            h.name       as hometown_name,
            s.department as department_id,
            d.name       as department_name
    from students s
            left join hometowns h on s.hometown = h.id
            left join departments d on s.department = d.id
    where s.id = :student_id;";

    $sth = $dbh->prepare($query);
    $sth->bindParam(':student_id', $student_id);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    if ($res == []) {
        print_r(json_encode([]));
        my_log("Error: invalid student id");
        return http_response_code(400);
    }

    $res = $res[0];
    $query_linked = "SELECT cs.id,
            c.name as course_name
    from courses_students cs
    left join courses c on cs.course = c.id
    where cs.student = :student_id;";

    $sth = $dbh->prepare($query_linked);
    $sth->bindParam(':student_id', $student_id);
    $sth->execute();
    $array_linked = $sth->fetchAll(PDO::FETCH_ASSOC);
    $res['linked_records'] = $array_linked;

    print_r(json_encode($res, JSON_UNESCAPED_UNICODE));
    return http_response_code(200);
}

get_student($dbh);
$dbh = null;
