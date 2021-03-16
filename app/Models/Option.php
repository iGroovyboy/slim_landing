<?php


namespace App\Models;


use App\Models\Model;
use App\Services\DB\DB;

class Option extends Model
{
    public const TABLE_NAME = 'options';

    public static function get(string $option, $default = null)
    {
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
