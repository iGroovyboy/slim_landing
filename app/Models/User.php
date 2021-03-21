<?php


namespace App\Models;

use App\Services\DB\DB;

class User extends Model
{
    public const TABLE_NAME = 'options';

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
    }

    public static function getByEmail(string $email)
    {
        $data = DB::query("SELECT * FROM {self::TABLE_NAME} WHERE email = ?", [$email])->first();
        if ($data) {
            return new self($data);
        }

        return null;
    }
}
