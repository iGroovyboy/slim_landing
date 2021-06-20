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

    private $parentKey;

    public function __construct($parentKey)
    {
//        DB::in(self::TABLE_NAME);
        $this->parentKey = $parentKey;
    }

    /**
     * @param string $string
     *
     * @return self
     */
    public static function of($parentKey = null)
    {
        return new self($parentKey);
    }

    private static function getNestedNodesById($id, $tbl)
    {
        $me    = __FUNCTION__;
        $items = DB::query("SELECT * FROM $tbl WHERE parent_key = '$id' ORDER BY `order`")->get();

        foreach ($items as $i => $item) {
            $items[$item['key']]          = Arr::only($item, ['value', 'items']);
            $items[$item['key']]['items'] = DB::query("SELECT * FROM $tbl WHERE parent_key = '{$item['id']}' ORDER BY `order`")->get();
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

    public function getAll()
    {
        $tbl = static::TABLE_NAME;
        $key = $this->parentKey;

        $data[$key] = DB::query("SELECT * FROM $tbl WHERE key = '$key'")->first();

        $data[$key]['items'] = static::getNestedNodesById($data[$key]['key'], $tbl);
        $data[$key] = Arr::only($data[$key], ['value', 'items']);
        static::unsetEmptyItems($data[$key]);

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
    public function get(string $key = null)
    {
        if (empty($key)) {
            return $this->getAll();
        }

        $parent = $this->parentKey;
        $result = DB::query("SELECT * FROM " . static::TABLE_NAME . " WHERE key = '$key' AND parent_key = '$parent'")->first();


        return $result['value'] ? Str::maybeUnserialize($result['value']) : null;
    }

    public function set(string $key, string $value)
    {
        $parent = $this->parentKey;

        if ($this->has($key)) {
            $value = DB::query("UPDATE " . self::TABLE_NAME . " SET value = '$value' WHERE key = '$key' AND parent_key = '$parent'")->exec();
        } else {
            $value = DB::query("INSERT INTO " . self::TABLE_NAME . " (key, value, parent_key) VALUES (?, ?, ?)", [$key, $value, $parent])->exec();
        }

        return $value;
    }

    public function has($key)
    {
        $parent = $this->parentKey;

        return (bool)DB::query("SELECT 1 FROM " . self::TABLE_NAME . " WHERE key = '$key' AND parent_key = '$parent' ")
                       ->first();
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
