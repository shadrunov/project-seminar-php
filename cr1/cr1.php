<?php

function create_connection(): PDO
{
    try {
        $dbh = new PDO('mysql:host=0.0.0.0:3307;dbname=prof202;charset=utf8', 'alex', 'docker');
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        $dbh = null;
        die();
    }
}


function get_1($dbh, string $s_name, int $year_start = 0, int $page = 0)
{
    $query = "SELECT students.lastname,
                     students.firstname,
                     `groups`.grnum,
                     s.speciality_name,
                     `groups`.year_start
    FROM students left join `groups` on students.grid=groups.grid
    left join specialities s on `groups`.speciality_id = s.speciality_id
    where s.speciality_name like :s_name and year_start like :y_start
    order by lastname asc limit :lim_begin, :lim_size;";

    $sth = $dbh->prepare($query);

    $s_name = $s_name . '%';
    if ($year_start == 0) {
        $year_start = '%';
    }

    $lim_size = 50;
    if ($page <= 0) {
        $lim_begin = 0;
    } else
        $lim_begin = $lim_size * ($page - 1);

    $sth->bindParam(':s_name', $s_name);
    $sth->bindParam(':y_start', $year_start);
    $sth->bindParam(':lim_begin', $lim_begin, PDO::PARAM_INT);
    $sth->bindParam(':lim_size', $lim_size, PDO::PARAM_INT);

    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    $j = json_encode($res, JSON_UNESCAPED_UNICODE);
    print_r($j);
}


function post_1($dbh, string $lastname, string $firstname, int $grid, int $age)
{
    // check if group_id is correct
    if ($grid < 0) {
        echo json_encode(['error' => 'grid is incorrect']);
        return 500;
    }

    // check if age is correct
    if ($grid < 0) {
        echo json_encode(['error' => 'age is incorrect']);
        return 500;
    }

    // check if name and surname are correct
    if ($firstname == '' or $lastname == '') {
        echo json_encode(['error' => 'name or surname are incorrect']);
        return 500;
    }

    // check if group_id is correct
    $query = "SELECT grid from `groups` where grid = :grid;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':grid', $grid);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (count($res) == 0) {
        echo json_encode(['error' => 'grid is incorrect']);
        return 500;
    }

    // insert
    $query = "INSERT into students (lastname, firstname, grid, age)
                values (:lastname, :firstname, :grid, :age);";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':lastname', $lastname);
    $sth->bindParam(':firstname', $firstname);
    $sth->bindParam(':grid', $grid, PDO::PARAM_INT);
    $sth->bindParam(':age', $age, PDO::PARAM_INT);
    $sth->execute();

    // find new entry
    $query = "SELECT id from students
              where lastname = :lastname and 
              firstname = :firstname and 
              grid = :grid and 
              age = :age;";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':lastname', $lastname);
    $sth->bindParam(':firstname', $firstname);
    $sth->bindParam(':grid', $grid, PDO::PARAM_INT);
    $sth->bindParam(':age', $age, PDO::PARAM_INT);
    $sth->execute();
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    $j = json_encode(
        ['success' => end($res)['id']]
    );
    print_r($j);
}


function run()
{
    $dbh = create_connection();

    // handle requests
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $speciality_name = '';
        $year_start = 0;
        $pagenum = 0;

        if (array_key_exists('speciality_name', $_GET) and is_string($_GET['speciality_name'])) {
            $speciality_name = $_GET['speciality_name'];

            if (array_key_exists('year_start', $_GET) and is_numeric($_GET['year_start']))
                $year_start = (int)$_GET['year_start'];

            if (array_key_exists('pagenum', $_GET) and is_numeric($_GET['pagenum']))
                $pagenum = (int)$_GET['pagenum'];

            try {
                get_1($dbh, $speciality_name, $year_start, $pagenum);
            } catch (PDOException $e) {
                echo "Error!: " . $e->getMessage() . PHP_EOL;
                $dbh = null;
            }
        } else
            echo json_encode(['error' => 'speciality_name is the compulsory argument']);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (
            array_key_exists('lastname', $_POST) and is_string($_POST['lastname']) and
            array_key_exists('firstname', $_POST) and is_string($_POST['firstname']) and
            array_key_exists('grid', $_POST) and is_numeric($_POST['grid']) and
            array_key_exists('age', $_POST) and is_numeric($_POST['age'])
        ) {
            try {
                post_1($dbh, $_POST['lastname'], $_POST['firstname'], (int)$_POST['grid'], (int)$_POST['age']);
            } catch (PDOException $e) {
                echo "Error!: " . $e->getMessage() . PHP_EOL;
                $dbh = null;
            }
        } else
            echo json_encode(['error' => 'some of compulsory arguments were omitted']);
    }

    $dbh = null;
}

run();
