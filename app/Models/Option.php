<?php


namespace App\Models;


use App\Models\Model;
use App\Services\DB\DB;
use App\Services\Str;

class Option extends Model
{
    public const TABLE_NAME = 'options';

    public function all($limit = null)
    {
        $limit = $limit ? ' LIMIT ' . $limit : '';
        $value = DB::query("SELECT * FROM ". static::TABLE_NAME . $limit)->first();

        return $value;
    }

    public function has($name)
    {
        return (bool)DB::query("SELECT 1 FROM " . static::TABLE_NAME . " WHERE name = '$name'")
           ->first();
    }

    public static function get(string $name, $default = null)
    {
        $value = DB::query("SELECT value FROM " . static::TABLE_NAME . " WHERE name = '$name'")->first();

        if ($value && Str::isJson($value['value'])) {
            return json_decode($value['value']);
        }

        return $value ?: $default;
    }

    public static function set(string $name, string $value)
    {
        $optionExists = static::get($name);
        if ($optionExists) {
            $value = DB::query("UPDATE ". static::TABLE_NAME . " SET value = '$value' WHERE name = '$name'")->exec();
        } else {
            $value = DB::query("INSERT INTO ". static::TABLE_NAME . " (name, value) VALUES (?, ?)", [$name, $value])->exec();
        }
        return $value;
    }

    public static function delete(string $name)
    {
        return DB::query("DELETE FROM ". static::TABLE_NAME . " WHERE name = '$name'")->exec();
    }

    public static function deleteAll()
    {
        return DB::query("DELETE FROM ". static::TABLE_NAME )->exec();
    }
}
