<?php

namespace App\Services;

use App\Models\Localization as Item;
use App\Services\LocalizationService as Service;
use Cache;

class LocalizationService extends abstractService
{

    /**
     * Repository constructor.
     */
    public function __construct(int $id = null)
    {
        $this->setItem(new Item);
        parent::__construct($id);
    }

    /**
     * @return bool
     */
    public function deletable(int $id = null): bool
    {


        if ($this->getPermissions()->has('setting', 'save')) {
            return true;
        } else {
            $this->deletableMsg = 'You dont have permission';
        }
        return false;
    }

    /**
     * @return bool
     */
    public function updatable(int $id = null): bool
    {
        if ($this->getPermissions()->has('setting', 'save')) {
            return true;
        } else {
            $this->updatableMsg = 'You dont have permission';
        }
        return false;
    }

    /**
     * Create table data for item.
     *
     * @param object $filters
     * @return array
     */
    public function getAllFiltered(object $filters): array
    {

        $filters = (object)$filters;
        $columns = ['id', 'variable', 'value'];

        $list = $this->item;

        // Search
        if (isset($filters->search)) {
            $value = $filters->search['value'];
            if ($value != '') {
                $search_text = explode(' ', $value);
                foreach ($search_text as $text) {
                    $list = $list->where(function ($list) use ($text) {
                        $list
                            ->orWhere('variable', 'LIKE', '%' . $text . '%')
                            ->orWhere('value', 'LIKE', '%' . $text . '%')
                            ->orWhere('id', $text);
                    });
                }
            }
        }

        if (isset($filters->lang)) {
            $list = $list->where('lang', $filters->lang);
        }

        return [$list, $columns];
    }

    /**
     * Scans *.blade files and insert localization variables to DB.
     *
     * @param string $dir -> './public/'
     * @return int sum of found variable
     */
    public function scan($dir = './'): int
    {

        $files = [];
        $checkDirectories = [
            "*",
            "*/*",
            "*/*/*",
            "*/*/*/*"
        ];

        foreach ($checkDirectories as $c) {
            $files = array_merge($files, glob($dir."../resources/views/" . $c . "blade.php"));
        }

        $content = '';
        foreach ($files as $file) {
            if (is_file($file)) {
                $content .= \File::get($file);
            }
        }

        $rendered = [];
        $contentArr = explode('_(', $content);
        if (count($contentArr) > 1) {
            unset($contentArr[0]);
            foreach ($contentArr as $item) {
                $new_item = explode(')', $item);
                $new_item = $new_item[0];
                foreach (['"', "'", " ", "\n", "\b", "\t"] as $char) {
                    $new_item = trim($new_item, $char);
                }
                $rendered[] = $new_item;
            }
        }

        foreach ($rendered as $variable) {
            foreach (langsAll() as $lang) {

                $variable = substr($variable, 0, 100);
                if (is_null($this->item->where('variable', $variable)->where('lang', $lang->id)->first())) {
                    $item = new Item;
                    $item->variable = $variable;
                    $item->lang = $lang->id;

                    // Add variable name directly as value (only for english)
                    if ($lang->id == 29) {
                        $item->value = $variable;
                    }
                    $item->save();
                }
            }
        }

        return count($rendered);
    }

    /**
     *
     */
    public function getByVariable(string $variable): ?object
    {
        return $this->item
            ->where('variable', $variable)
            ->get();
    }

    /**
     * This method is required for resources\lang\en\app.php
     *
     * @param int lang_id -> must be DB:nixe_config_lang.id
     */
    public function langVariables($lang_id): array
    {
        $return = Cache::remember('lang.' . $lang_id, 0, function () use ($lang_id) {
            $return = [];

            $lang_data = Item::where('lang', $lang_id)->get();

            if (!is_null($lang_data)) {
                foreach ($lang_data as $lang_variables) {
                    if ($lang_variables->value != '') {
                        $value = $lang_variables->value;
                    } else {
                        $value = str_replace('_', ' ', $lang_variables->variable);
                    }
                    $return[str_replace('app.', '', $lang_variables->variable)] = $value;
                }
            }
            return $return;
        });
        return $return;
    }
}
