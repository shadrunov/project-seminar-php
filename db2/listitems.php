<?php

require_once 'init.php';

// list students
function list_students($dbh)
{
    if ($_SERVER['REQUEST_METHOD'] != 'GET') {
        my_log("Error: expected GET, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }


    $query = "SELECT s.id,
        s.name,
        s.hometown   as hometown_id,
        h.name       as hometown_name,
        s.department as department_id,
        d.name       as department_name
    from students s
        left join hometowns h on s.hometown = h.id
        left join departments d on s.department = d.id;";


    $sth = $dbh->prepare($query);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    print_r(json_encode($res, JSON_UNESCAPED_UNICODE));
}

list_students($dbh);
$dbh = null;
