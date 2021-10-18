<?php declare(strict_types=1);

function getInitials(string $FIO): string | null
{   
    $FIO = trim($FIO);

    if ($FIO == "")
        return null;

    $arr = explode(" ", $FIO);
    if (count($arr) < 2)
        return null;

    $res = mb_convert_case($arr[0], MB_CASE_TITLE, "UTF-8")." ";

    $arr = array_slice($arr, 1);
    foreach ($arr as $m) {
        foreach (explode("-", $m) as $n) {
            $res = $res.mb_strtoupper(mb_substr($n, 0, 1))."-";
        }
        $res = mb_substr($res, 0, mb_strlen($res)-1).".";
    }
    return $res;
}


// getInitials("мамин-сибиряк дмитрий наркисович");
// getInitials("Петров иван");
// getInitials("Маркес Габриэль Хосе Гарсиа");
// getInitials("Смирнов Теодор-Арсений");
// getInitials("Смирнов Теодор-Арсений Ларионов-Тришкин");