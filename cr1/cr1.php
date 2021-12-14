<?php

try {
    $dbh = new PDO('mysql:host=0.0.0.0:3307;dbname=prof202;charset=utf8', 'alex', 'docker');
    // освобожение ресурса
    // $dbh = null;
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    // $query = "SELECT students.lastname, students.firstname, `groups`.grnum, s.speciality_name, `groups`.year_start FROM students left join `groups` on students.grid=groups.grid left join specialities s on `groups`.speciality_id = s.speciality_id where s.speciality_name like 'ИБ' order by lastname asc;";

    // $sth = $dbh->prepare($query);

    // $sth->bindParam(':s_name', $s_name);

    // $res = $sth->execute();
    // $arrayRes = $res->fetchAll();
    // echo($res);


    } catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    $dbh = null;
    die();
    }

function get_1($dbh, string $s_name)
{
    
    $query = "SELECT students.lastname,
                     students.firstname,
                     `groups`.grnum,
                     s.speciality_name,
                     `groups`.year_start
    FROM students left join `groups` on students.grid=groups.grid
    left join specialities s on `groups`.speciality_id = s.speciality_id
    where s.speciality_name like :s_name
    order by lastname asc limit 10;";

    $sth = $dbh->prepare($query);

    $sth->bindParam(':s_name', $s_name);

    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    $j = json_encode($res, JSON_UNESCAPED_UNICODE);
    print_r($j);

}


if ( $_SERVER['REQUEST_METHOD'] === 'GET' )
{
    if ( array_key_exists('speciality_name', $_GET) and is_string($_GET['speciality_name']))
    {
        // print_r($_GET);
        // print_r( gettype($_GET['speciality_name']) );
        // echo "<p> requested page 1 </p>";
        get_1($dbh, $_GET['speciality_name']);
        // echo 'done';
    }
}

$dbh = null;