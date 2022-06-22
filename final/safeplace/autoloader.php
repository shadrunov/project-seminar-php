<?php

if (!defined('MODULES_DIR')) {
    throw new RuntimeException('check settings');
}

// зарегистрировать автозагрузчик, который подключает файл с именем класса
spl_autoload_register(
    function ($class_name) {
        $split = explode('\\', $class_name);
        $location = MODULES_DIR . implode('/', $split) . '.php';
       
        if (file_exists($location)) {
            if (DEBUG) {
                echo "AUTOLOAD: trying to create " . $class_name . ', require ' . $location . '<br>' . PHP_EOL;
            }
            require $location;
        }
        // } else {
        //     echo getcwd() . '<br>';
        //     echo $class_name . '<br>';
        //     print_r(realpath(MODULES_DIR) . '<br>');
        //     print_r($location . '<br>');
        //     // echo '<br>';
        //     // print_r(scandir(getcwd()));
        //     echo '<br>';
        //     print_r(scandir(MODULES_DIR));
        //     echo '<br>';
        //     print_r(scandir(MODULES_DIR . 'myapp/Logger'));
        //     echo '<br>';
        //     print_r(realpath(MODULES_DIR . $class_name) . '<br>');
        //     print_r(realpath(MODULES_DIR . 'myapp/Logger/LoggerBuffer.php') . '<br>');
        //     echo "NO AUTOLOAD: trying to create " . $class_name . ', require ' . MODULES_DIR . $class_name . '.php<br>' . PHP_EOL;
        //     require MODULES_DIR . $class_name . '.php';
        // }
    }
);
