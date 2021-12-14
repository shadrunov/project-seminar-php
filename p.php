<?php

// phpinfo();
$mysqli = new mysqli('0.0.0.0', 'alex', 'docker', 'lms', 3307);

if ($mysqli->connect_error) {
die('Ошибка подключения (' . $mysqli->connect_errno . ') '
. $mysqli->connect_error);
}

echo 'Соединение установлено... ' . $mysqli->host_info . " server " . $mysqli->get_server_info() . PHP_EOL . PHP_EOL;
$charset = $mysqli->get_charset();
echo "charset ". $charset->comment . ' collation '.$charset->collation.PHP_EOL;

// printf("Изначальная кодировка: %s\n", $mysqli->character_set_name());
// /* изменение набора символов на utf8mb4 */
// if (!$mysqli->set_charset("utf8mb4")) {
// printf("Ошибка при загрузке набора символов utf8mb4: %s\n", $mysqli->error);
// // exit(); // обработка ошибки
// } else {
// printf("Текущий набор символов: %s\n", $mysqli->character_set_name());
// }

$mysqli->select_db("lms");

// if ($result = $mysqli->query("SELECT * FROM Students LIMIT 10")) {
//     printf("Select вернул %d строк.\n", $result->num_rows);
//     $rows = $result->fetch_all(); // $result->fetch_all(MYSQLI_ASSOC)
//     print_r($rows);
//     /* очищаем результирующий набор */
//     $result->close();
//     }

if ($result = $mysqli->query("SELECT * FROM Students LIMIT 10")) {
    printf("Select вернул %d строк.\n", $result->num_rows);
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    print_r($rows);
    /* очищаем результирующий набор */
    $result->close();
}