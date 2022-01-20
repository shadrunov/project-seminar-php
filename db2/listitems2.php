<?php

require_once 'init.php';

// list groups
function list_groups($dbh)
{
    if ($_SERVER['REQUEST_METHOD'] != 'GET') {
        my_log("Error: expected GET, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }


    $query = "SELECT c.id,
        c.name,
        c.department as department_id,
        d.name       as department_name,
        c.rating
    from courses c
        left join departments d on c.department = d.id;";


    $sth = $dbh->prepare($query);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    print_r(json_encode($res, JSON_UNESCAPED_UNICODE));
}

list_groups($dbh);
$dbh = null;
