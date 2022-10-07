<?php

namespace App\Services\Traits;

trait TableTrait
{
    /**
     * item DB table name = class name
     *
     * @return string
     */
    public static function tableName($withPrefix = true): string
    {
        $table = (new static)->item->getTable();
        if (!$withPrefix) {
            $table = str_replace(env('DB_TABLE_PREFIX'), '', $table);
        }
        return $table;
    }

    /**
     * Returns all field names in models db table as array
     *
     * @return array
     */
    public function tableFieldNames(): array
    {
        return \Schema::getColumnListing($this->tableName(true));
    }

    /**
     * Returns all fields data in models table as array.
     *
     * @return array
     */
    public function tableFields(): array
    {
        return \DB::select("SHOW COLUMNS FROM " . $this->tableName());
    }

    /**
     * Returns language table name of the model.
     *
     * @return string
     */
    public function langTableName(): string
    {
        $tableName = $this->tableName();
        $prefix = env('DB_TABLE_PREFIX');
        $expected_lang_table_name = $prefix . \Str::plural(\Str::singular($tableName) . '_lang');
        return $expected_lang_table_name;
    }

    /**
     * Required post fields to save item.
     *
     * @return array
     */
    public static function requiredFields(): array
    {
        $item = (new static)->get();
        $tableName = $item->getTable();
        return $item->getConnection()->getSchemaBuilder()->getColumnListing($tableName);
    }
}
