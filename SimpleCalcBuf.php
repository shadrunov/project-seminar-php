<?php

class SimpleCalcBuf
{
    public float $result;

    public function __construct(float $init = 0)
    {
        $this->result = $init;
    }

    public function add(float $a): SimpleCalcBuf
    {
        $this->result += $a;
        return $this;
    }

    public function multiply(float $a): SimpleCalcBuf
    {
        $this->result *= $a;
        return $this;
    }

    public function substract(float $a): SimpleCalcBuf
    {
        $this->result -= $a;
        return $this;
    }

    public function divide(float $a, int $precision = 2): SimpleCalcBuf
    {
        $this->result = round($this->result / $a, $precision);
        return $this;
    }
    public function getResult()
    {
        return $this->result;
    }
}

$calc = new SimpleCalcBuf(10);

echo $calc->add(1)->getResult() . PHP_EOL;
echo $calc->multiply(2)->getResult() . PHP_EOL;
echo $calc->substract(1)->getResult() . PHP_EOL;
echo $calc->divide(3)->getResult() . PHP_EOL;
echo $calc->divide(20, 1)->getResult() . PHP_EOL;
// echo SimpleCalcBuf::divide(20, 0).PHP_EOL;
echo 'example' . PHP_EOL;
$a = new SimpleCalcBuf(10);
echo $a->getResult() . PHP_EOL; // 10
echo $a->add(4.4)->getResult() . PHP_EOL; // 14.4;
echo $a->substract(10.2)->getResult() . PHP_EOL; // 4.2;
echo $a->multiply(2)->getResult() . PHP_EOL; // 8.4;
echo $a->divide(5, 1)->getResult() . PHP_EOL; // 1.7;