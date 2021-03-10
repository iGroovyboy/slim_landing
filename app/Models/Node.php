<?php


namespace App\Models;


use App\Services\DB\DB;

class Node
{
	public const TABLE_NAME = 'nodes';

    protected static string $type;
    protected static $id;

    public function __construct()
    {
//        DB::in(self::TABLE_NAME);
    }


    /**
     * @param string $type
     * @param int|string $id
     *
     * @return Node
     */
    public static function of(string $type, $id): Node
    {
        self::$type = $type;
        self::$id   = $id;

        return new self;
    }

    /**
     * @param string $type
     * @param int|string $id
     */
    public function get(string $type, $id)
    {
    }

    public function getAll(string $type)
    {
    }

    public function update(string $type, $id, $value = null)
    {
        DB::delete($type, $id)->get();
    }

    public function delete(string $type, $id)
    {
        DB::delete($type, $id)->get();
    }

    public function deleteAll(string $type)
    {
        DB::deleteAll();
    }

    public function create(string $type, $slug = null, $value = null)
    {
        DB::insertRow($type, $slug, $value)->get();
    }

	public function test( string $string ) {
    	DB::test('cookoo');
	}

}