<?php

namespace App\Services\DB;

use App\Services\Config;

final class DBConfig
{
    public const DSN_DRIVER = 'driver';
    public const DSN_HOST = 'host';
    public const DSN_DBNAME = 'dbname';
    public const DSN_PATH = 'path';
    public const DSN_PORT = 'port';
    public const DSN_UNIX_SOCKET = 'unix_socket';
    public const DSN_CHARSET = 'charset';
    public const DSN_USERNAME = 'username';
    public const DSN_PASSWORD = 'password';

    public ?string $driver;

    public ?string $host;

    public ?string $path;

    public $port;

    public ?string $dbname;

    public ?string $unix_socket; //cant be used with host or port

    public ?string $charset;

    public ?string $username;

    public ?string $password;

    public ?array $options; // PDO options

    /**
     * DbConfig constructor.
     *
     * @param string $driver
     * @param string $host
     * @param string $path
     * @param mixed $port
     * @param string $dbname
     * @param string $unix_socket
     * @param string $charset
     * @param string $username
     * @param string $password
     */
    public function __construct($db) {
        $this->driver      = $db[self::DSN_DRIVER];

        $this->host        = $db[self::DSN_HOST];
        $this->path        = $db[self::DSN_PATH];
        $this->port        = $db[self::DSN_PORT] ?: DB::getPort($this->driver);
        $this->dbname      = $db[self::DSN_DBNAME];
        $this->unix_socket = $db[self::DSN_UNIX_SOCKET] ?? '';
        $this->charset     = $db[self::DSN_CHARSET] ?? '';

        $this->username    = $db[self::DSN_USERNAME];
        $this->password    = $db[self::DSN_PASSWORD];

        $this->options     = $db['options'];
    }

    public function getDsn()
    {
        $port = '';
        if ( ! empty($p = self::DSN_PORT)) {
            $port = "port=$p;";
        }

        if (DB::DRIVER_SQLITE === $this->driver) {
            $dsn = "sqlite:$this->path";
        } elseif (DB::DRIVER_MYSQL === $this->driver) {
            $dsn = "mysql:host={$this->host};{$port}dbname={$this->dbname};charset=utf8";
        } elseif (DB::DRIVER_PGSQL === $this->driver) {
            $dsn = "pgsql:host={$this->host};{$port}dbname={$this->dbname}";
        }

        return $dsn;
    }

}
