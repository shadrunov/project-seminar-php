<?php declare(strict_types=1);

function getBMI(int $height, $weight): float | null
{
    /* провека типов
        тип первого аргумента проверяется автоматически
        тип второго аргумента проверим вручную, так как php 
        преобразует int во float даже при включенном strict_types=1.
        мы хотим этого избежать!
    */
    if (!is_float($weight))  // в том числе int
        throw new TypeError("FUNC ACCEPTS FLOAT ONLY FOR WEIGHT!!! Argument #1 (\$height) must be of type int"); 

    /* провека значений
        10 <= $height <= 300
        1.0 <= $weight <= 300.0
    */
    if ($height > 300 or $height < 10 or $weight > 300 or $weight < 10)  // here php can compare float and int
        return null;

    $height = $height * 0.01;
    $i = $weight / $height / $height;

    return round($i, 2, PHP_ROUND_HALF_UP);
}


echo getBMI(170, 77.0);