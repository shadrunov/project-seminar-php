<?php

namespace myapp\Controller;

use myapp\Controller\DBClass;
use PDO;
use Exception;

class ItemClass extends DBClass
{
    public function router()
    {
        session_start([
            'use_strict_mode' => true,
            'cookie_httponly' => true,
            'cookie_samesite' => 'Strict',
            'name' => 'securecookie'
        ]);

        if (!isset($_SESSION['is_auth'])) {
            $this->security_logger->logEvent("Access error: attempt of unauthorised access", __FILE__, __LINE__, __FUNCTION__);
            header('Location: ' . 'index.php?page=login', true, 302);
            return http_response_code(302);
        }

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                if (array_key_exists('ID', $_GET) and is_numeric($_GET['ID']))
                    if (array_key_exists('action', $_GET) and $_GET['action'] == 'edit')
                        return $this->show_edit();  // show edit page
                    elseif (array_key_exists('action', $_GET) and $_GET['action'] == 'delete')
                        return $this->show_delete();  // show delete page
                    else
                        return $this->get();  // get item

                if (array_key_exists('action', $_GET) and $_GET['action'] == 'add')
                    return $this->show_add();  // show add page
                return $this->list();  //list items
                break;

            case 'POST':
                if (array_key_exists('ID', $_GET) and is_numeric($_GET['ID']))
                    if (array_key_exists('action', $_GET) and $_GET['action'] == 'delete')
                        return $this->delete();  // delete item

                if (array_key_exists('action', $_GET) and $_GET['action'] == 'edit')
                    return $this->edit();  // edit item
                return $this->add();  // additem
                break;
            default:
                throw new Exception("Error: expected GET or POST, not " . $_SERVER['REQUEST_METHOD']);
                break;
        }
    }

    private function list()
    {

        $student_name = '%';
        $hometown_name = '%';
        $department_name = '%';
        $items_per_page = 30;
        $page_number = 1;
        $lim_begin = 0;
        $old_params = [];
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

        $sth = $this->dbh->prepare($query);
        $sth->bindParam(':student_name', $student_name);
        $sth->bindParam(':hometown_name', $hometown_name);
        $sth->bindParam(':department_name', $department_name);
        $sth->bindParam(':lim_begin', $lim_begin, PDO::PARAM_INT);
        $sth->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);
        $sth->execute();
        $students = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $this->twig->render('listitems.html', [
            'old_params' => $old_params,
            'students' => $students,
            'hometowns' => $this->hometowns(),
            'departments' => $this->departments(),
            'username' => $_SESSION['username']
        ]);
    }

    private function hometowns()
    {
        $query = "select distinct name from hometowns;";
        return $this->queryFetchOne($query);
    }

    private function departments()
    {
        $query = "select distinct name from departments;";
        return $this->queryFetchOne($query);
    }

    private function get()
    {
        $student_id = $_GET['ID'];
        $query = "SELECT s.id,
                s.name       as student_name,
                s.hometown   as hometown_id,
                h.name       as hometown_name,
                s.department as department_id,
                d.name       as department_name
        from students s
                left join hometowns h on s.hometown = h.id
                left join departments d on s.department = d.id
        where s.id = :student_id;";
        $student = $this->queryFetchAll($query, [':student_id' => $student_id]);

        if ($student == []) {
            http_response_code(400);
            throw new Exception("Error: invalid student id");
        }

        $student = $student[0];
        $query_linked = "SELECT c.name as course_name
        from courses_students cs
        left join courses c on cs.course = c.id
        where cs.student = :student_id;";

        $courses = $this->queryFetchOne($query_linked, [':student_id' => $student_id]);

        return $this->twig->render('getitem.html', [
            'student' => $student,
            'courses' => $courses,
            'username' => $_SESSION['username']
        ]);
    }

    private function show_edit()
    {
        $query = "SELECT s.id as student_id,
            s.name       as student_name,
            s.hometown   as hometown_id,
            h.name       as hometown_name,
            s.department as department_id,
            d.name       as department_name
            from students s
                    left join hometowns h on s.hometown = h.id
                    left join departments d on s.department = d.id
            where s.id = :student_id;";

        $old_params = $this->queryFetchAll($query, [':student_id' => $_GET['ID']]);

        $render_params = [
            'hometowns' => $this->hometowns(),
            'departments' => $this->departments(),
            'username' => $_SESSION['username']
        ];

        if (sizeof($old_params) > 0)
            $render_params['old_params'] = $old_params[0];
        else
            $render_params['error'] = 'this record not found';

        return $this->twig->render('edititem.html', $render_params);
    }

    private function edit()
    {
        if (!(array_key_exists('student_name', $_POST) and
            array_key_exists('hometown_name', $_POST) and
            array_key_exists('department_name', $_POST) and
            array_key_exists('student_id', $_POST) and
            is_numeric($_POST['student_id'])
        )) {
            http_response_code(400);
            throw new Exception("Error: can't edit, not all params recieved");
        }

        $student_name = $_POST['student_name'];
        $student_id = $_POST['student_id'];
        $hometown_id = 0;
        $department_id = 0;
        $old_params = [
            'student_name' => $student_name,
            'hometown_name' => $_POST['hometown_name'],
            'department_name' => $_POST['department_name'],
            'student_id' => $_POST['student_id']
        ];

        // check if hometown is correct
        $query = "SELECT id from hometowns where name = :hometown;";
        $res = $this->queryFetchAll($query, [':hometown' => $_POST['hometown_name']]);

        if (count($res) == 0) {
            http_response_code(400);
            return $this->twig->render('edititem.html', [
                'old_params' => $old_params,
                'error' => 'hometown is incorrect',
                'hometowns' => $this->hometowns(),
                'departments' => $this->departments(),
                'username' => $_SESSION['username']
            ]);
        }

        $hometown_id = $res[0]['id'];

        // check if department is correct
        $query = "SELECT id from departments where name = :department;";
        $res = $this->queryFetchAll($query, [':department' => $_POST['department_name']]);
        if (count($res) == 0) {
            http_response_code(400);
            return $this->twig->render('edititem.html', [
                'old_params' => $old_params,
                'error' => 'department is incorrect',
                'hometowns' => $this->hometowns(),
                'departments' => $this->departments(),
                'username' => $_SESSION['username']
            ]);
        }
        $department_id = $res[0]['id'];

        // check if student ID is correct
        $query = "SELECT name, hometown, department from students where id = :id;";
        $res = $this->queryFetchAll($query, [':id' => $student_id]);
        if (count($res) == 0) {
            http_response_code(400);
            return $this->twig->render('edititem.html', [
                'old_params' => $old_params,
                'error' => 'record not found',
                'hometowns' => $this->hometowns(),
                'departments' => $this->departments(),
                'username' => $_SESSION['username']
            ]);
        }

        // edit
        $new_student_name = $res[0]["name"];
        $new_hometown_id = $res[0]["hometown"];
        $new_department_id = $res[0]["department"];
        if ($student_name != '')
            $new_student_name = $student_name;
        if ($hometown_id > 0)
            $new_hometown_id = $hometown_id;
        if ($department_id > 0)
            $new_department_id = $department_id;


        $query = "UPDATE students
            set name = :name, 
            hometown = :hometown,
            department = :department
            where id = :id;";
        $sth = $this->dbh->prepare($query);
        $sth->bindParam(':name', $new_student_name);
        $sth->bindParam(':hometown', $new_hometown_id, PDO::PARAM_INT);
        $sth->bindParam(':department', $new_department_id, PDO::PARAM_INT);
        $sth->bindParam(':id', $student_id, PDO::PARAM_INT);
        $sth->execute();
        header('Location: ' . 'index.php?page=item&ID=' . $student_id, true, 302);
    }

    private function show_add()
    {
        return $this->twig->render('additem.html', [
            'hometowns' => $this->hometowns(),
            'departments' => $this->departments(),
            'username' => $_SESSION['username']
        ]);
    }

    private function add()
    {
        if (!(array_key_exists('student_name', $_POST) and is_string($_POST['student_name']) and
            array_key_exists('hometown_name', $_POST) and array_key_exists('department_name', $_POST)
        )) {
            http_response_code(400);
            throw new Exception("Error: can't add, not all params recieved");
        }

        $student_name = $_POST['student_name'];
        $hometown_id = 0;
        $department_id = 0;
        $old_params = [
            'student_name' => $student_name,
            'hometown_name' => $_POST['hometown_name'],
            'department_name' => $_POST['department_name']
        ];

        // check if hometown is correct
        $query = "SELECT id from hometowns where name = :hometown;";
        $res = $this->queryFetchAll($query, [':hometown' => $_POST['hometown_name']]);
        if (count($res) == 0) {
            http_response_code(400);
            return $this->twig->render('additem.html', [
                'old_params' => $old_params,
                'error' => 'hometown is incorrect',
                'hometowns' => $this->hometowns(),
                'departments' => $this->departments(),
                'username' => $_SESSION['username']
            ]);
        }
        $hometown_id = $res[0]['id'];

        // check if department is correct
        $query = "SELECT id from departments where name = :department;";
        $res = $this->queryFetchAll($query, [':department' => $_POST['department_name']]);

        if (count($res) == 0) {
            http_response_code(400);
            return $this->twig->render('additem.html', [
                'old_params' => $old_params,
                'error' => 'department is incorrect',
                'hometowns' => $this->hometowns(),
                'departments' => $this->departments(),
                'username' => $_SESSION['username']
            ]);
        }
        $department_id = $res[0]['id'];

        // insert
        $query = "INSERT into students (name, hometown, department)
                values (:name, :hometown, :department);";
        $sth = $this->dbh->prepare($query);
        $sth->bindParam(':name', $student_name);
        $sth->bindParam(':hometown', $hometown_id, PDO::PARAM_INT);
        $sth->bindParam(':department', $department_id, PDO::PARAM_INT);
        $sth->execute();

        // find new entry
        $query = "SELECT id from students
              where name = :name and 
              hometown = :hometown and 
              department = :department;";
        $sth = $this->dbh->prepare($query);
        $sth->bindParam(':name', $student_name);
        $sth->bindParam(':hometown', $hometown_id, PDO::PARAM_INT);
        $sth->bindParam(':department', $department_id, PDO::PARAM_INT);
        $sth->execute();
        $res = $sth->fetchAll(PDO::FETCH_ASSOC);
        header('Location: ' . 'index.php?page=item&ID=' . end($res)["id"], true, 302);
    }

    private function show_delete()
    {
        $query = "SELECT id as student_id from students where id = :student_id;";
        $students = $this->queryFetchAll($query, [':student_id' => $_GET['ID']]);

        if (sizeof($students) > 0)
            return $this->twig->render('deleteitem.html', [
                'student' => $students[0],
                'username' => $_SESSION['username']
            ]);
        return $this->twig->render('deleteitem.html', [
            'error' => 'this record not found',
            'username' => $_SESSION['username']
        ]);
    }

    private function delete()
    {
        $student_id = $_POST['ID'];

        // check if M-M doesn't exist
        $query = "SELECT id from courses_students where student = :student_id;";
        $res = $this->queryFetchAll($query, [':student_id' => $student_id]);
        if (count($res) > 0) {
            http_response_code(400);
            throw new Exception("Error: link exists, can't delete");
        }

        // check if record exist
        $query = "SELECT id from students where id = :student_id;";
        $res = $this->queryFetchAll($query, [':student_id' => $student_id]);
        if (count($res) == 0) {
            http_response_code(400);
            throw new Exception("Error: invalid student id");
        }

        $query = "DELETE 
                from students 
                where id = :student_id;";

        $this->queryFetchAll($query, [':student_id' => $student_id]);
        header('Location: ' . 'index.php?page=item', true, 302);
    }
}
