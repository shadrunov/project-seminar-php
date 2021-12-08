<?php

declare(strict_types=1);

function typesCounter(...$args): array | null
{
    $res = ['boolean' => 0, 'integer' => 0, 'float' => 0, 'string' => 0, 'object' => 0, 'array' => 0];

    foreach ($args as $var) {
        $type = gettype($var);
        if ($type == 'double')
            $type = 'float';
        if (array_key_exists($type, $res))
            $res[$type] += 1;
        else
            return NULL;
    }
    return $res;
}


// print_r(typesCounter(1, 3, 'test', 7, 'another string', 7.16, 1.2e3, 'hoho', 10, true));
// print_r(typesCounter('test', new StdClass, false));
// print_r(typesCounter('test', 1111, null));
