<?php

namespace myapp\Controller;

use myapp\Logger\LoggerInterface;
use PDO;

abstract class DBClass
{
    protected PDO $dbh;
    protected LoggerInterface $logger;
    protected LoggerInterface $security_logger;
    protected \Twig\Environment $twig;

    public function __construct(PDO $dbh, LoggerInterface $logger, LoggerInterface $security_logger, \Twig\Environment $twig)
    {
        $this->dbh = $dbh;
        $this->logger = $logger;
        $this->security_logger = $security_logger;
        $this->twig = $twig;
    }

    protected function queryFetchAll($query, $queryParams)
    {
        $this->logger->logEvent("query: " . $query, __FILE__, __LINE__, __FUNCTION__);
        $this->logger->logEvent("params: " . var_export($queryParams, true), __FILE__, __LINE__, __FUNCTION__);
        // подготовка запроса
        $sth = $this->dbh->prepare($query);
        // выполнение запроса
        $sth->execute($queryParams);
        return $sth->fetchAll();
    }

    protected function queryFetchOne($query, $queryParams = [])
    {
        $this->logger->logEvent("query: " . $query, __FILE__, __LINE__, __FUNCTION__);
        $this->logger->logEvent("params: " . var_export($queryParams, true), __FILE__, __LINE__, __FUNCTION__);
        // подготовка запроса
        $sth = $this->dbh->prepare($query);
        // выполнение запроса
        $sth->execute($queryParams);
        return $sth->fetchAll(PDO::FETCH_COLUMN, 0);
    }
}
