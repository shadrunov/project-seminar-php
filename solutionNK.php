<?php declare(strict_types=1);

function getSumNK($input, int $N, int $K): int
{   
    if (count($input) == 0)
        return 0;

    if ($N < 0 or $K < 0 or count($input) < $N + $K - 1)
        return -1;

    foreach ($input as $value) {
        if (!is_int($value))
            return -1;
    }
    
    if ($N == 0)
    {
        $N = count($input);
        $K = 1;
    }

    if ($K == 0)
        $K = 1;

    $sliced = array_slice($input, $K - 1, $N);
    $sum = 0;
    foreach ($sliced as $value)
        $sum += $value;
    return $sum;
}

// echo getSumNK($value = [1, 2, 3, 4, 5], $N = 1, $K = 2);
// echo PHP_EOL;
// echo getSumNK($value = [1,3,4,5,8,9], $N = 3, $K = 2);
// echo PHP_EOL;
// echo getSumNK($value = [1,3], $N = 2, $K = 2);

// echo PHP_EOL;

// echo PHP_EOL;

// // echo PHP_EOL;
// $input = [1,3,4,5,8,9];
// // print getSumNK($input, 1, 0.5).PHP_EOL; //TypeError
// // print getSumNK($input, 1.5, 2).PHP_EOL; //TypeError
// print getSumNK($input, 3, 2).PHP_EOL; //12
// print getSumNK($input, 0, 3).PHP_EOL; //30
// print getSumNK($input, 1, 0).PHP_EOL; //1
// print getSumNK($input, 1, 1).PHP_EOL; //1
// print getSumNK($input, 6, 2).PHP_EOL; //-1
// print getSumNK($input, -2, 2).PHP_EOL; //-1
// print getSumNK($input, 3, -1).PHP_EOL; //-1
// $input = [1,3,4];
// print getSumNK($input, 1, 3).PHP_EOL; //4
// $input = [-5=>1, 'cat'=>3, 2.0=>1];
// print getSumNK($input, 0, 0).PHP_EOL; //5
// print getSumNK([], 0, 0).PHP_EOL; //5

