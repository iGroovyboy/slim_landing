<?php


namespace App\Services\DB;


use App\Services\Config;
use PDO;
use PDOStatement;

class DB
{
    public const DRIVER_SQLITE = 'sqlite';
    public const DRIVER_MYSQL = 'mysql';
    public const DRIVER_PGSQL = 'pgsql';

    protected static $query = '';
    protected static $args = [];
    /**
     * @var DBConfig
     */
    protected static DBConfig $config;

    protected $st = '';

    protected static $driver = '';
    protected static string $path = '';

    protected static PDO $pdo;

    public function __construct($query, $args)
    {
        self::$query = $query;
        self::$args  = $args;

        if ( ! self::isConnected()) {
            throw new \Exception('Db is not connected');
        }
        $pdoStatement = self::$pdo->prepare($query);
        /* @var PDOStatement $pdoStatement */
        foreach ($args as $k => $arg) {
            $pdoStatement->bindValue($k + 1, $arg, self::getType($arg));
        }

        $this->st = $pdoStatement;
    }

    public static function setDriver($driver)
    {
        self::$driver = $driver;

        if (DB::DRIVER_SQLITE === self::$driver) {
            self::$path = Config::getPath('app/paths/db', Config::get('app/paths/dbfilename'));
        }
    }

    public static function start($db)
    {
        $db['path']    = self::$path;
        $db['options'] = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        self::$config = new DBConfig($db);

        self::$pdo = new PDO(
            self::$config->getDsn(),
            self::$config->username,
            self::$config->password,
            self::$config->options
        );
    }

    public static function isConnected()
    {
        return ! empty(self::$pdo); //self::$pdo instanceof PDO;
    }

    public static function setConfig()
    {
    }

    public static function getConfig()
    {
        return (array)self::$config;
    }

    public static function migrate($dir)
    {
        $ext = self::$driver === self::DRIVER_SQLITE ? 'sqlite' : 'sql';

        $files = array_filter(
            array_diff(scandir($dir), array('..', '.', 'readme.md')),
            function ($file) use ($ext) {
                return pathinfo($file, PATHINFO_EXTENSION) === $ext;
            }
        );

        $migrated = [];
        foreach ($files as $file) {
            $migration = file_get_contents($dir . $file);

            if ( ! $migration || empty($migration)) {
                continue;
            }

            $migrated[] = DB::query($migration)->exec();
            // TODO add logging
            // ..
        }

        return $migrated;
    }

    protected static function driver_mysql()
    {

    }

    public static function query(string $query, $args = []): self
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

    public static function getPort($driver)
    {
        $ports = [
            self::DRIVER_MYSQL  => 3306,
            self::DRIVER_PGSQL  => 5432,
            self::DRIVER_SQLITE => '',
        ];

        return $ports[$driver];
    }
}
