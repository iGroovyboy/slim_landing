<?php


namespace App\Models;

use App\Services\DB\DB;

class User extends Model
{
    public const TABLE_NAME = 'users';

    public int $id;
    public string $email;
    public int $role;
    public string $hash;

    public function __construct($data)
    {
        $this->email = $data->email;
        $this->if    = $data->id;
        $this->role  = $data->role;
        $this->hash  = $data->hash;
        $this->tableName  = self::TABLE_NAME;
    }

    public static function getByEmail(string $email)
    {
        $table_name = self::TABLE_NAME;
        $data = DB::query("SELECT * FROM {$table_name} WHERE email = ?", [$email])->first();
        if ($data) {
            return new self($data);
        }

        return null;
    }

    public static function add(string $email, string $hash, $role_id)
    {
        $table_name = self::TABLE_NAME;
        // add user to db
        $insert = DB::query("INSERT INTO {$table_name} (email, hash, role_id) VALUES (?, ?, ?)", [$email, $hash, $role_id])->exec();

        // TODO notify by email
        // ..

        return $insert;
    }
}
