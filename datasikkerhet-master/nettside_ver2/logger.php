<?php

require('/../../../home/datasikkerhet/vendor/autoload.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\LogglyHandler;
use Monolog\Formatter\LogglyFormatter;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;

use Monolog\Handler\GelfHandler;
use Gelf\Message;
use Monolog\Formatter\GelfMessageFormatter;

$logger = new Monolog\Logger('sikkerhet');

// Fillogging
$logger->pushHandler(new StreamHandler(__DIR__.'/test_log/log.txt', Logger::DEBUG));

// GELF
$transport = new Gelf\Transport\UdpTransport("127.0.0.1", 12201 /*, Gelf\Transport\UdpTransport::CHUNK_SIZE_LAN*/);
$publisher = new Gelf\Publisher($transport);
$handler = new GelfHandler($publisher,Logger::DEBUG);

$logger->pushHandler($handler);

?>