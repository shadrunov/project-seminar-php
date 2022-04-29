<?php

class ClassCounter
{
    private static int $calls = 0;
    private static int $objects = 0;

    /**
     * Вернет количество существующих на данные момент объектов класса
     * @return int - количество объектов
     */
    public static function getObjectsNum(): int
    {
        return self::$objects;
    }

    /**
     * Вернет количество вызовов метода callMethod на текущий момент
     * @return int количество вызовов метода callMethod на текущий момент
     */
    public static function getMethodCallNum(): int
    {
        return self::$calls;
    }

    public function __construct()
    {
        self::$objects += 1;
    }

    public function __destruct()
    {
        self::$objects -= 1;
    }

    public function __call(string $name, array $arguments): void
    {
        if ($name == 'callMethod')
            self::$calls += 1;
    }

    public static function __callStatic(string $name, array $arguments): void
    {
        if ($name == 'callMethod')
            self::$calls += 1;
    }
}

$a = new ClassCounter();
echo ClassCounter::getObjectsNum() . PHP_EOL; // 1
$a->callMethod();
echo ClassCounter::getMethodCallNum() . PHP_EOL; //1
$a->callMethod();
echo ClassCounter::getMethodCallNum() . PHP_EOL; //2
$b = new ClassCounter();
echo ClassCounter::getObjectsNum() . PHP_EOL; // 2
$b->callMethod();
echo ClassCounter::getMethodCallNum() . PHP_EOL; //3
unset($a);
echo ClassCounter::getObjectsNum() . PHP_EOL; // 1