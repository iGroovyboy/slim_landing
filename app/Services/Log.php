<?php


namespace App\Services;


use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Log
{
    protected static $logger; /* @var LoggerInterface $name  */

    public static function boot($logger)
    {
        self::$logger = new $logger();

        return self::$logger;
    }

    public static function theme($message)
    {
        self::$logger->log(LogLevel::WARNING, 'THEME: ' . $message);
    }

    public static function __callStatic($method, $args)
    {
        if (method_exists(self::$logger, $method)) {
            $message = strtoupper($method) . ': ' . $args[0] ?? '';

            self::$logger->$method($message);
        }


        return self::$logger;
    }

}
