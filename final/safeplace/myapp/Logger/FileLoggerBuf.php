<?php

namespace myapp\Logger;

class FileLoggerBuf extends BaseLogger
{
    protected string $fileName;
    protected $fileHandler; // must be resource

    /**
     * FileLoggerBuf constructor.
     * @param string $fileName - название файла
     * @param int $bufferSize - размер буфера
     */
    public function __construct(string $fileName, int $bufferSize )
    {
        if( !file_exists( $fileName ) ) {
            //throw exception
        }
        $this->fileName = $fileName;
        $this->openFileForLog();
//        $this->setBufferSize( $bufferSize );
        parent::__construct(LoggerInterface::TYPE_FILE_BUFFER);
    }

    /**
     * Проверяет, что передан ресурс, и устанавливает значение свойства fileHandler
     * @param $fileHandler
     */
    protected function setFileHandler( $fileHandler )
    {
        if( !is_resource( $fileHandler ) ) {
            throw new TypeError('invalid argument, must be a resource for fileHandler');
        }
        $this->fileHandler = $fileHandler;
    }

    protected function openFileForLog()
    {
        $fileHandler = fopen( $this->fileName, 'a' );
        $this->setFileHandler($fileHandler);
    }

    public function logEvent(string $message, string $file, int $line, string $function)
    {
        // TODO: Implement logEvent() method.
        $logRecordTxt = $this->prepareLogRecord4file( $message, $file, $line, $function );
        // write to file
        fwrite($this->fileHandler, $logRecordTxt);
    }

    protected function prepareLogRecord4file( string $message, string $file, int $line, string $function )
    {
        $lr = $this->prepareLogRecord( $message, $file, $line, $function );
        return "LOG type(".$this->getType().") [".$lr['date']."]: ".$lr['message']." at ".$lr['file']." line ".$lr['line'].PHP_EOL;
    }


    /**
     * Деструктор класса
     * освободит файловый дескриптор
     */
    function __destruct()
    {
        // освободить ресурс
        fclose($this->fileHandler);
    }
}

//$logger = new FileLoggerBuf('tmp.log', 3);
//$logger->logEvent('Test event 1', __FILE__, __LINE__, __FUNCTION__);
//$logger->logEvent('Test event 2', __FILE__, __LINE__, __FUNCTION__);
//$logger->logEvent('Test event 3', __FILE__, __LINE__, __FUNCTION__);
//// в этот момент логгер должен сохранить 3 записи в файл tmp.log
//$logger->logEvent('Test event 4', __FILE__, __LINE__, __FUNCTION__);
//$logger->logEvent('Test event 5', __FILE__, __LINE__, __FUNCTION__);
//$logger->logEvent('Test event 6', __FILE__, __LINE__, __FUNCTION__);
//// в этот момент логгер должен сохранить следующие 3 записи в файл tmp.log
