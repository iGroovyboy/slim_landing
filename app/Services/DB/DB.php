<?php


namespace App\Services\DB;


class DB
{
	protected static $driver = '';

	public static function setDriver( $driver ) {
		self::$driver = $driver;
	}

    public static function query(): DB
    {
    }

	public static function test( string $string )
	{
//		$tableName = self::getCallerTableName();
		self::getCallerTableName();
    	echo "<br>DB_test. Table: x. <br>Driver = " . self::$driver . "<br>arg: $string";
	}

	public function get()
    {
    }

    public function first()
    {
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
