<?php


namespace App\Services\DB;


use App\Services\Config;
use PDO;
use PDOStatement;

class DB
{
    protected static $query = '';
    protected static $args = [];

    protected $st = '';

    protected static $driver = '';

    protected static PDO $pdo;

    public function __construct($query, $args)
    {
        $this->query = $query;
        $this->args  = $args;

        $pdoStatement = self::$pdo->prepare($query);
        /* @var $pdoStatement PDOStatement */
        foreach ($args as $k => $arg) {
            $pdoStatement->bindValue($k, $arg, self::getType($arg));
        }

        $this->st = $pdoStatement;
    }

    public static function setDriver($driver)
    {
        self::$driver = $driver;
    }

    public static function start($db)
    {
        if ('sqlite' === $db['driver']) {
            $path = Config::getPath('app/db', 'database.db');
            $dsn  = "sqlite:host={$db['host']};charset=utf8";
        } elseif ('mysql' === $db['driver']) {
            $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset=utf8";
        } elseif ('pgsql' === $db['driver']) {
            $dsn = "pgsql:host={$db['host']};dbname={$db['dbname']};charset=utf8";
            //port=5432;
        }

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        self::$pdo = new PDO($dsn, $db['user'], $db['pass'], $options);
    }

    protected static function driver_mysql()
    {
    }

    public static function query(string $query, $args): self
    {
        return new self($query, $args);
    }

    public static function getType($var)
    {
        $type = gettype($var);

        $pdoTypes = [
            'boolean'      => PDO::PARAM_BOOL,
            'integer'      => PDO::PARAM_INT,
            'double'       => PDO::PARAM_BOOL,
            'string'       => PDO::PARAM_STR,
            'array'        => PDO::PARAM_STR,
            'object'       => PDO::PARAM_STR,
            'NULL'         => PDO::PARAM_NULL,
            'unknown type' => PDO::PARAM_STR,
        ];

        return $pdoTypes[$type] ?? PDO::PARAM_STR;
    }

    public static function test(string $string)
    {
        $tableName = self::getCallerTableName();
        self::getCallerTableName();
        echo "<br>DB_test. Table: $tableName. <br>Driver = " . self::$driver . "<br>arg: $string";
    }

    public function get()
    {
        $this->st->execute();

        return $this->st->fetchAll();
    }

    public function first()
    {
        $this->st->execute();

        return $this->st->fetch();
    }

    public function exec()
    {
        return $this->st->execute();
    }

    protected static function getCallerTableName()
    {
        $backtrace = debug_backtrace();
        $caller    = $backtrace[2]['object'];

        return $caller::TABLE_NAME;
    }
}
