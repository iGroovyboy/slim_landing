<?php


namespace App\Models;


use App\Services\DB\DB;

class Node extends Model
{
    public const TABLE_NAME = 'nodes';

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

    public static function createTable()
    {
        $sql = "
        CREATE TABLE `nodes` (
            `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
            `type` VARCHAR(64) NOT NULL,
            `slug` TINYTEXT,
            `value` LONGTEXT,
            `parent_id` BIGINT,
            PRIMARY KEY `id`
        ) ENGINE=MyISAM;
        ";

        $sqlite = "
        CREATE TABLE `nodes` (
            `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
            `type` VARCHAR(64) NOT NULL,
            `slug` TINYTEXT,
            `value` LONGTEXT,
            `parent_id` BIGINT,
            PRIMARY KEY `id`
        )";
    }

    // Node::of('Page', 3 )::get();
    // Node::of('Page', 3 )::get('element', 'li');
    public static function get(string $option = null, $default = null)
    {
        $parentEmpty = empty(self::$parentType);
        $childEmpty  = empty($option);

        $hasOnlyParent = ! $parentEmpty && $childEmpty;
        $hasOnlyChild  = $parentEmpty && ! $childEmpty;

        if ($hasOnlyParent || $hasOnlyChild) {
            //get
        } else {
            //get + get
        }


        $value = DB::query("SELECT value FROM {self::TABLE_NAME} WHERE name = '$option'")->first();

        return $value ?: $default;
    }

    public static function update(string $option, string $value)
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
