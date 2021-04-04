<?php


namespace App\Models;


use App\Models\Model;
use App\Services\DB\DB;

class Option extends Model
{
    public const TABLE_NAME = 'options';

    public function all($limit = null)
    {
        $limit = $limit ? ' LIMIT ' . $limit : '';
        $value = DB::query("SELECT * FROM ". self::TABLE_NAME . $limit)->first();

        return $value;
    }

    public static function get(string $name, $default = null)
    {
        $value = DB::query("SELECT value FROM ". self::TABLE_NAME . " WHERE name = '$name'")->first();

        return $value ?: $default;
    }

    public static function set(string $name, string $value)
    {
        $optionExists = self::get($name);
        if ($optionExists) {
            $value = DB::query("UPDATE ". self::TABLE_NAME . " SET value = '$value' WHERE name = '$name'")->exec();
        } else {
            $value = DB::query("INSERT INTO ". self::TABLE_NAME . " (name, value) VALUES (?, ?)", [$name, $value])->exec();
        }
        return $value;
    }

    public static function delete(string $name)
    {
        return DB::query("DELETE FROM ". self::TABLE_NAME . " WHERE name = '$name'")->exec();
    }

    public static function deleteAll()
    {
        return DB::query("DELETE FROM ". self::TABLE_NAME )->exec();
    }
}
