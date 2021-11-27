<?php

declare(strict_types=1);

function wordsCount(string $sourceString): array
{

    function custom_mb_in_array($_needle, array $_hayStack)
    {
        foreach ($_hayStack as $value) {
            if ($value == $_needle) {
                return true;
            }
        }
        return false;
    }

    if ($sourceString == "")
        return [];

    $arr1 = mb_split(PHP_EOL, $sourceString);
    $arr = [];
    foreach ($arr1 as $key) {
        $arr = array_merge($arr, mb_split(" ", $key));
    }
    $res = [];

    foreach ($arr as $word) {
        // word without any symbols
        $clear_word = '';
        for ($i = 0; $i < mb_strlen($word); $i++) {
            if (!custom_mb_in_array(mb_substr($word, $i, 1), [' ', '-', ',', '.', ';', ':', '“', '’', '"', "'"]))
                $clear_word = $clear_word . mb_substr($word, $i, 1);
        }

        if ($clear_word) {
            if (array_key_exists($clear_word, $res))
                $res[$clear_word] += 1;
            else {
                $res[$clear_word] = 1;
            }
        }
    }

    function swap(&$array, $key_a, $key_b)
    {
        list($array[$key_a], $array[$key_b]) = array($array[$key_b], $array[$key_a]);
    }



    function greater(string $a, string $b): bool
    {
        $order = [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
            'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
            'w', 'x', 'y', 'z', 'а', 'б', 'в', 'г', 'д', 'е', 'ё',
            'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р',
            'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы',
            'ь', 'э', 'ю', 'я'
        ];

        $len = max(mb_strlen($a), mb_strlen($b));
        for ($i = 0; $i < $len - 1; $i++) {
            if (!(custom_mb_in_array(mb_strtolower(mb_substr($a, $i, 1)), $order) and custom_mb_in_array(mb_strtolower(mb_substr($b, $i, 1)), $order)))
                throw new ValueError('symbol not found' . $a . $b);

            if (array_search(mb_strtolower(mb_substr($a, $i, 1)), $order) > array_search(mb_strtolower(mb_substr($b, $i, 1)), $order))
                return true;

            if (array_search(mb_strtolower(mb_substr($a, $i, 1)), $order) < array_search(mb_strtolower(mb_substr($b, $i, 1)), $order))
                return false;
        }
        return true;
    }

    function bubbleSort($arr)
    {
        $n = count($arr);
        $keys = array_keys($arr);

        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if (greater($keys[$j], $keys[$j + 1]))
                    swap($keys, $j, $j + 1);
            }
        }

        $res = [];
        foreach ($keys as $key) {
            $res[$key] = $arr[$key];
        }
        return $res;
    }


    try {
        $res = bubbleSort($res);
    } catch (ValueError) {
        ksort($res, SORT_FLAG_CASE | SORT_STRING);
    }
    return $res;
}


// print_r( wordsCount("мамин-сибиряк  дмитрий на'ркисович дмитрий дмитрий 母亲 母亲 الآب الآب") ) ;
// print_r( wordsCount("Петров иван Иван иван") ) ;
// print_r( wordsCount("Маркес Габриэль Хосе Гарсиа") ) ;
// print_r( wordsCount("ale’x Alex baba Baba") ) ;
print_r(wordsCount("Раз Два Три Четыре Пять
Скажем без подвоха
Раз Два Три Четыре Пять
Жадность - это плохо"));
