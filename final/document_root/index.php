<?php

require_once '../safeplace/settings.php';
require_once  MODULES_DIR . 'autoloader.php';

use myapp\EventHandler;
use myapp\Logger\FileLoggerBuf;

$logger = new FileLoggerBuf('../info.log', 1);
$security_logger = new FileLoggerBuf('../security.log', 1);

try {
    $app = new EventHandler($dbSettings, $logger, $security_logger, $twig);
    $app->run();
} catch (Exception $e) {
    $logger->logEvent($e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
    echo json_encode([]);
}
