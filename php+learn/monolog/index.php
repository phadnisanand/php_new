<?php

require __DIR__."/vendor/autoload.php"; // This tells PHP where to find the autoload file so that PHP can load the installed packages

use Monolog\Formatter\JsonFormatter;
use Monolog\Logger; // The Logger instance
use Monolog\Handler\StreamHandler; // The StreamHandler sends log messages to a file on your disk
/*
$logger = new Logger("daily");
$stream_handler = new StreamHandler("php://stdout");
$logger->pushHandler($stream_handler);
*/
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;

$logger = new Logger("my_logger");
$formatter = new JsonFormatter();

// Create new handler
$rotating_handler = new RotatingFileHandler(__DIR__ . "/log/debug.log", 30, Level::Debug);
$stream_handler = new StreamHandler(__DIR__ . "/log/notice.log", Level::Notice);


$stream_handler->setFormatter($formatter);
$rotating_handler->setFormatter($formatter);
// Push the handler to the log channel
$logger->pushHandler($stream_handler);
$logger->pushHandler($rotating_handler);

// Log the message
$logger->debug("This is a debug message.");
$logger->info("This is an info level message.");
$logger->notice("This is a notice level message.");
$logger->warning("This is a warning level message.");
$logger->error("This is an error level message.");
$logger->critical("This is a critical level message.");
$logger->alert("This is an alert level message.");
$logger->emergency("This is an emergency level message.");