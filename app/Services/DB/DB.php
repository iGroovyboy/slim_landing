<?php


namespace App\Services\DB;


class DB
{
	protected $query = '';

	protected static $driver = '';

    public function __construct($query)
    {
        $this->query = $query;
	}

	public static function setDriver( $driver ) {
		self::$driver = $driver;
	}

    public static function start()
    {

	}

    public static function query(string $query): DB
    {
        return new self($query);
    }

	public static function test( string $string )
	{
		$tableName = self::getCallerTableName();
		self::getCallerTableName();
    	echo "<br>DB_test. Table: $tableName. <br>Driver = " . self::$driver . "<br>arg: $string";
	}

	public function get()
    {
    }

    public function first()
    {
        return [];
    }

    public function exec()
    {
        return 1;
    }

    protected function prepare()
    {

    }

    protected static function getCallerTableName(){
	    $backtrace = debug_backtrace();
	    $caller    = $backtrace[2]['object'];

	    return $caller::TABLE_NAME;
    }
}
