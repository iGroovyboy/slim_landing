<?php


namespace App\Models;


use App\Services\Arr;
use App\Services\DB\DB;
use App\Services\Str;

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

    private static function getNestedNodesById($id, $tbl)
    {
        $me    = __FUNCTION__;
        $items = DB::query("SELECT * FROM $tbl WHERE parent_id = '$id' ORDER BY `order`")->get();

        foreach ($items as $i => $item) {
            $items[$item['key']]          = Arr::only($item, ['value', 'items']);
            $items[$item['key']]['items'] = DB::query("SELECT * FROM $tbl WHERE parent_id = '{$item['id']}' ORDER BY `order`")->get();
            self::unsetEmptyItems($items[$item['key']]);

            foreach ($items[$item['key']]['items'] as $c => $child) {
                $items[$item['key']]['items'][$child['key']]          = Arr::only($child, ['value', 'items']);
                $items[$item['key']]['items'][$child['key']]['items'] = self::$me($child['id'], $tbl);
                self::unsetEmptyItems($items[$item['key']]['items'][$child['key']]);
                unset($items[$item['key']]['items'][$c]);
            }

            unset($items[$i]);
        }

        return $items;
    }

    public static function getAllFor($key)
    {
        $tbl = self::TABLE_NAME;

        $data[$key] = DB::query("SELECT * FROM $tbl WHERE key = '$key'")->first();

        $data[$key]['items'] = self::getNestedNodesById($data[$key]['id'], $tbl);
        $data[$key] = Arr::only($data[$key], ['value', 'items']);
        self::unsetEmptyItems($data[$key]);

        return $data;
    }

    public static function unsetEmptyItems(&$var)
    {
        if(empty($var['items'])){
            unset($var['items']);
        }
    }

    // Node::of('Page', 3 )::get();
    // Node::of('Page', 3 )::get('element', 'li');
    public static function get(string $key = null)
    {
        $parentEmpty = empty(self::$parentType);
        $childEmpty  = empty($key);

        $hasOnlyParent = ! $parentEmpty && $childEmpty;
        $hasOnlyChild  = $parentEmpty && ! $childEmpty;

        if ($hasOnlyParent || $hasOnlyChild) {
            // get once
            $result = DB::query("SELECT * FROM " . self::TABLE_NAME . " WHERE key = '$key'")->first();
        } else {
            $result = DB::query("SELECT * FROM " . self::TABLE_NAME . " WHERE key = '$key' AND parent_id = '1' ")->first();
        }

        return Str::maybeUnserialize($result->value);
    }

    public static function set(string $key, string $value)
    {
        $keyExists = self::get($key);
        if ($keyExists) {
            $value = DB::query("UPDATE " . self::TABLE_NAME . " SET value = '$value' WHERE key = '$key'")->exec();
        } else {
            $value = DB::query("INSERT INTO " . self::TABLE_NAME . " (key, value) VALUES (?, ?)", [$key, $value])->exec();
        }

        return $value;
    }

    public static function delete(string $option)
    {
        return DB::query("DELETE FROM " . self::TABLE_NAME . " WHERE name = '$option'")->exec();
    }

    public static function deleteAll()
    {
        return DB::query("DELETE FROM " . self::TABLE_NAME)->exec();
    }


}
