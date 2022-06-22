<?php

namespace myapp\Logger;

class LoggerBuffer extends BaseLogger
{
    use TBuffer {
        addItem as protected;
        getItem as protected;
        getBufferSize as protected;
    }

    /**
     * LoggerBuffer constructor.
     * @param int $bufferSize - buffersize
     */
    public function __construct(int $bufferSize)
    {
        $this->setBufferSize($bufferSize);
        parent::__construct(LoggerInterface::TYPE_LOGGER_BUFFER);
    }

    /**
     * @inheritDoc
     */
    public function logEvent(string $message, string $file, int $line, string $function)
    {
        if (DEBUG) {
            $this->addItem($this->prepareLogRecord($message, $file, $line, $function));
            if ($this->getCurrentBufferSize() >= $this->getBufferSize()) {
                $this->printLog();
            }
        }
    }

    /**
     * распечатает события лога из буфера
     */
    public function printLog()
    {
        while ($logRecord = $this->getItem()) {
            $logRecordTxt = $this->getLogTextVerbose($logRecord);
            echo $logRecordTxt . "<br>";
        }
    }
}
