<?php


namespace App\Models;


use App\Services\DB\DB;

class Node extends Model
{
    public const TABLE_NAME = 'nodes';

    public const TYPE_FIELD = 'field';
    public const TYPE_BLOCK = 'block';

    public function __construct()
    {
//        DB::in(self::TABLE_NAME);
    }

    /**
     * @param string $type
     * @param int|string $key
     *
     * @return self
     */
    public static function of(string $type = null, $key = null)
    {
        static::$parentType = $type;
        static::$parentId   = $key;

        return new static();
    }

    public function test(string $string)
    {
        DB::test('cookoo');
    }

    // Node::of('Page', 3 )::get();
    // Node::of('Page', 3 )::get('element', 'li');
    public static function get(string $key = null, $default = null)
    {

        $parentEmpty = empty(self::$parentType);
        $childEmpty  = empty($option);

        $hasOnlyParent = ! $parentEmpty && $childEmpty;
        $hasOnlyChild  = $parentEmpty && ! $childEmpty;

        if ($hasOnlyParent || $hasOnlyChild) {
            // get once
            $value = DB::query("SELECT * FROM " . self::TABLE_NAME . " WHERE key = '$key'")->first();
        } else {
            //get + get
        }


        $value = DB::query("SELECT value FROM {self::TABLE_NAME} WHERE name = '$key'")->first();

        return $value ?: $default;
    }

    public static function set(string $option, string $value)
    {
        $optionExists = self::get($option);
        if ($optionExists) {
            $value = DB::query("UPDATE {self::TABLE_NAME} SET value = '$value' WHERE name = '$option'")->exec();
        } else {
            $value = DB::query("INSERT INTO value FROM {self::TABLE_NAME} WHERE name = '$option'")->exec();
        }
    }

    public static function delete(string $option)
    {
        return DB::query("DELETE FROM {self::TABLE_NAME} WHERE name = '$option'")->exec();
    }

    public static function deleteAll()
    {
        return DB::query("DELETE FROM {self::TABLE_NAME}")->exec();
    }


}
