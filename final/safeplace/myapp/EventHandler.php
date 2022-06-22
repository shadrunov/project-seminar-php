<?php

namespace myapp;

use myapp\Controller;
use myapp\Logger\LoggerInterface;
use myapp\Exceptions\SecurityException;
use PDO;
use Exception;

class EventHandler
{
    private string $page;
    private PDO $dbh;
    protected LoggerInterface $logger;
    protected LoggerInterface $security_logger;
    private \Twig\Environment $twig;

    public function __construct(array $dbSettings, LoggerInterface $logger, LoggerInterface $security_logger, \Twig\Environment $twig)
    {
        $this->logger = $logger;
        $this->security_logger = $security_logger;
        $this->twig = $twig;
        $this->page = array_key_exists('page', $_GET) ? $_GET['page'] : 'default';
        // инициализация базы
        $this->initDB($dbSettings['connectionString'], $dbSettings['dbUser'], $dbSettings['dbPwd']);
    }

    private function initDB(string $connectionString, string $dbUser, string $dbPwd)
    {
        // создание подключения через connection_string с указанием типа базы
        $this->dbh = new PDO($connectionString, $dbUser, $dbPwd);
        $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->logger->logEvent('Connected to DB!', __FILE__, __LINE__, __FUNCTION__);
    }

    /**
     * call handler to process request
     */
    public function run()
    {
        try {
            $this->logger->logEvent('curpage: ' . $this->page, __FILE__, __LINE__, __FUNCTION__);
            switch ($this->page) {
                case 'login':
                    $login = new Controller\LoginClass($this->dbh, $this->logger, $this->security_logger, $this->twig);
                    echo $login->router();
                    break;
                case 'item':
                    $item = new Controller\ItemClass($this->dbh, $this->logger, $this->security_logger, $this->twig);
                    echo $item->router();
                    break;
                case 'logout':
                    $item = new Controller\LogoutClass($this->dbh, $this->logger, $this->security_logger, $this->twig);
                    echo $item->router($this->page);
                    break;
                default:
                    $item = new Controller\ItemClass($this->dbh, $this->logger, $this->security_logger, $this->twig);
                    echo $item->router();
                    // echo "default action!!!!";
                    break;
            }
        } catch (SecurityException $e) {
            $this->security_logger->logEvent($e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
            echo json_encode([]);
        } catch (Exception $e) {
            $this->logger->logEvent($e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }
}
