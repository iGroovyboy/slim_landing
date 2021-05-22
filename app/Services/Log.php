<?php


namespace App\Services;


use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Log
{
    protected static $logger;
    /* @var LoggerInterface $name */

    protected static $writers;

    public static function boot($logger)
    {
        self::$logger = new $logger();

        return self::$logger;
    }

    public static function theme($message)
    {
        self::$logger->log(LogLevel::WARNING, 'THEME: ' . $message);
        self::runWriters(LogLevel::WARNING, 'THEME: ' . $message);
    }

    public static function __callStatic($method, $args)
    {
        if (method_exists(self::$logger, $method)) {
            $message = strtoupper($method) . ': ' . $args[0] ?? '';

            self::$logger->$method($message);
            self::runWriters($method, $message);
        }

        return self::$logger;
    }

    public static function runWriters($message, $level = null)
    {
        foreach (self::$writers as $writer) {
            $writer->write($message, self::getLevelName($level));
        }
    }

    protected function getLevelName($level = null)
    {
        if (empty($level)) {
            return null;
        }

        $map = [
            'EMERGENCY' => LogLevel::EMERGENCY,
            'ALERT'     => LogLevel::ALERT,
            'CRITICAL'  => LogLevel::CRITICAL,
            'ERROR'     => LogLevel::ERROR,
            'WARNING'   => LogLevel::WARNING,
            'NOTICE'    => LogLevel::NOTICE,
            'INFO'      => LogLevel::INFO,
            'DEBUG'     => LogLevel::DEBUG,
        ];

        return $map[strtoupper($level)];
    }

    public static function setWriter($writer)
    {
        self::$writers[] = $writer;
    }

}
