<?php

require_once 'init.php';


// list students
function list_students($dbh)
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

    
    if ($_SERVER['REQUEST_METHOD'] != 'GET') {
        my_log("Error: expected GET, not " . $_SERVER['REQUEST_METHOD']);
        return http_response_code(405);
    }

    $student_name = '%';
    $hometown_name = '%';
    $department_name = '%';
    $items_per_page = 30;
    $page_number = 1;
    $lim_begin = 0;
    $old_params = [];
    // var_dump($_GET);
    if (array_key_exists('student_name', $_GET) and $_GET['student_name'] != '') {
        $student_name = $_GET['student_name'];
        $old_params['student_name'] = $_GET['student_name'];
    }
    if (array_key_exists('hometown_name', $_GET) and $_GET['hometown_name'] != '') {
        $hometown_name = $_GET['hometown_name'];
        $old_params['hometown_name'] = $_GET['hometown_name'];
    }
    if (array_key_exists('department_name', $_GET) and $_GET['department_name'] != '') {
        $department_name = $_GET['department_name'];
        $old_params['department_name'] = $_GET['department_name'];
    }
    if (array_key_exists('items_per_page', $_GET) and is_numeric($_GET['items_per_page']) and $_GET['items_per_page'] != '' and intval($_GET['items_per_page']) > 0) {
        $items_per_page = $_GET['items_per_page'];
        $old_params['items_per_page'] = $_GET['items_per_page'];
    }
    if (array_key_exists('page_number', $_GET) and is_numeric($_GET['page_number']) and $_GET['page_number'] != '' and intval($_GET['page_number']) > 0) {
        $page_number = $_GET['page_number'];
        $lim_begin = $items_per_page * ($page_number - 1);
        $old_params['page_number'] = $_GET['page_number'];
    }


    $query = "SELECT s.id as id,
        s.name       as student_name,
        s.hometown   as hometown_id,
        h.name       as hometown_name,
        s.department as department_id,
        d.name       as department_name
    from students s
        left join hometowns h on s.hometown = h.id
        left join departments d on s.department = d.id
    where s.name like :student_name and
          h.name like :hometown_name and
          d.name like :department_name
    limit :lim_begin, :items_per_page;";


    $sth = $dbh->prepare($query);
    $sth->bindParam(':student_name', $student_name);
    $sth->bindParam(':hometown_name', $hometown_name);
    $sth->bindParam(':department_name', $department_name);
    $sth->bindParam(':lim_begin', $lim_begin, PDO::PARAM_INT);
    $sth->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);
    $sth->execute();
    $students = $sth->fetchAll(PDO::FETCH_ASSOC);

    $hometowns = hometowns($dbh);
    $departments = departments($dbh);


    $loader = new \Twig\Loader\FilesystemLoader('./templates');

    $options = [
        'debug' => true, // включение/выключение отладки
        'cache' => false // включение/выключение кэширования
    ];
    $twig = new \Twig\Environment($loader, $options);

    echo $twig->render('listitems.html', [
        'old_params' => $old_params,
        'students' => $students,
        'hometowns' => $hometowns,
        'departments' => $departments,
        'username' => $_SESSION['username']
    ]);
}

list_students($dbh);
$dbh = null;
