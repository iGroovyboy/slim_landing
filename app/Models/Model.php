<?php


namespace App\Models;


use App\Services\DB\DB;

class Model
{
    protected static $tableName;

    protected static $parentType;
    protected static $parentId;

    /**
     * Sets base working table for Model class ONLY.
     * That's why it is **private** and why it is **self** (no late static binded need for uninhertited meth)
     * @param string $tableName
     *
     * @return static
     */
//    private static function from(string $tableName): self
//    {
//        self::$tableName = $tableName;
//        return new self();
//    }


    /**
     * @param string $childType
     * @param int|string $childKey
     */
//    public static function get($childType = null, $childKey = null)
//    {
//        if(!empty($parentType) && !empty($parentId)){
//
//        }
//
//        DB::query("WHERE ")
//
//        static $parentType = null;
//        static $parentId = null;
//    }

//    public function update(string $type, $id, $value = null)
//    {
//        DB::delete($type, $id)->get();
//    }

//    public function delete(string $type, $id)
//    {
//        DB::delete($type, $id)->get();
//    }

//    public function getAll(string $type)
//    {
//    }


//

//
//    public function deleteAll(string $type)
//    {
//        DB::deleteAll();
//    }
//
//    public function create(string $type, $slug = null, $value = null)
//    {
//        DB::insertRow($type, $slug, $value)->get();
//    }

}
