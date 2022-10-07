<?php

namespace App\Entities\Eloquent\Traits;

trait LangTrait
{

    /**
     * Returns language table name of the model.
     * 
     * @return string
     */
    public function langTableName(): string
    {
        return $this->service->langTableName();
    }

    /**
     * Returns a spesific language field.
     * 
     * Must be called after getById()
     * 
     * @return string
     */
    public function lang(string $field, ?int $lang = NULL): string
    {
        if (is_null($this->item)) {
            return '';
        }

        $id = $this->item->id;
        if (is_null($lang)) {
            $lang = $this->lang;
        }
        $sql =  "
                SELECT " . $field . " 
                FROM " . self::langTableName() . "
                WHERE lang='" . $lang . "' AND type_id=" . intval($id) . " 
                LIMIT 1;";
        $query = \DB::select($sql);
        if (!empty($query)) {
            if (isset($query[0]->{$field})) {
                return $query[0]->{$field};
            }
        }


        return '';
    }
}
