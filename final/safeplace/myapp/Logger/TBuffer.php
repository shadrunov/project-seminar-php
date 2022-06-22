<?php

namespace myapp\Logger;

trait TBuffer
{
    private array $buffer=[];
    private int $bufferSize;

    /**
     * @param int $bufferSize
     */
    private function setBufferSize(int $bufferSize): void
    {
        $this->bufferSize = $bufferSize;
    }

    /**
     * Добавление элемента наверх буфера. будет успешно, если стек еще не заполнен
     * если стк переполнен, элемент добавлен не будет
     * @param $item - элемент, который нужно добавить
     * @return bool - true если элемент добавлен в стек, иначе false
     */
    public function addItem($item ): bool
    {
        if( count( $this->buffer ) < $this->bufferSize ) {
            array_push( $this->buffer, $item );
            return true;
        }
        return false;
    }

    /**
     * Вытащить верхний элемент из начала буфера и вернуть его
     * Если стек пустой, вернет NULL
     * @return mixed пурвый элемент их буфера
     */
    public function getItem( )
    {
        return array_shift( $this->buffer );
    }

    /**
     * Вернет общий размер стека (максимальный)
     * @return int - максимальный размер стека
     */
    public function getBufferSize(): int
    {
        return $this->bufferSize;
    }

    /**
     * Вернет количество элементов, которые сейчас находятся в стеке
     * @return int - количество элементов в стеке
     */
    public function getCurrentBufferSize(): int
    {
        return count( $this->buffer );
    }
}

