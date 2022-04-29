<?php

class SimpleCalc
{
    static public function add(float $a, float $b): float
    {
        return $a + $b;
    }

    static public function multiply(float $a, float $b): float
    {
        return $a * $b;
    }

    static public function substract(float $a, float $b): float
    {
        return $a - $b;
    }

    static public function divide(float $a, float $b): float
    {
        return round($a / $b, 2);
    }
}

echo SimpleCalc::add(1, -1.5) . PHP_EOL;
echo SimpleCalc::multiply(1, -1.5) . PHP_EOL;
echo SimpleCalc::substract(1, -1.5) . PHP_EOL;
echo SimpleCalc::divide(1, -1.5) . PHP_EOL;
echo SimpleCalc::divide(20, 4) . PHP_EOL;
// echo SimpleCalc::divide(20, 0).PHP_EOL;